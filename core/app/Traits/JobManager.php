<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\Category;
use App\Models\Job;
use App\Models\Province;
use App\Models\Ward;
use App\Models\JobImage;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

trait JobManager
{
    protected function pageTitle($searchKey)
    {
        $title = "All Jobs";

        if ($searchKey) {
            $title = "Job Search : '$searchKey'";
        }
        return $title;
    }

    public function jobs($sellerId = 0, $type = null)
    {
        $search = trim(strtolower(request()->search));
        $type = $type ?? request()->type;
        $query = Job::query();

        if ($type) {
            $query->where('job_type', $type);
        }

        if ($sellerId) {
            $query = $query->sellers();
        }

        $query = $query->with(['province', 'industry']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('company_name', 'like', "%$search%");
            });
        }

        $data['jobs'] = $query->orderBy('id', 'desc')->paginate(getPaginate());
        $data['pageTitle'] = $this->pageTitle($search);

        return $data;
    }

    public function pendingJobs($sellerId = 0)
    {
        $search = trim(strtolower(request()->search));
        $query = Job::query();

        if ($sellerId) {
            $query = $query->sellers();
        }

        $query = $query->with(['province', 'industry']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('company_name', 'like', "%$search%");
            });
        }

        $data['jobs'] = $query->where('status', Status::DISABLE)->orderByDesc('id')->paginate(getPaginate());
        $data['pageTitle'] = 'Pending Jobs';
        $data['emptyMessage'] = "No job found";
        return $data;
    }

    public function jobByVendor($admin = true)
    {
        $search = trim(strtolower(request()->search));
        $query = Job::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('company_name', 'like', "%$search%");
            });
        }

        if ($admin) {
            $data['pageTitle'] = 'Jobs By Admin';
            $query = $query->where('seller_id', 0);
        } else {
            $data['pageTitle'] = 'Jobs By Seller';
            $query = $query->where('seller_id', '!=', 0);
        }

        $data['jobs'] = $query->orderByDesc('id')->paginate(getPaginate());
        return $data;
    }

    public function jobCreate()
    {
        $data['industries'] = \App\Models\JobIndustry::active()->orderBy('name')->get();
        $data['levels']     = \App\Models\JobLevel::active()->orderBy('name')->get();
        $data['provinces']  = Province::orderBy('name')->get();
        $data['pageTitle']  = "Add New Job";
        return $data;
    }

    public function editJob($id, $sellerId = 0)
    {
        if ($sellerId) {
            $data['job'] = Job::where('seller_id', $sellerId)->where('id', $id)->firstOrFail();
        } else {
            $data['job'] = Job::where('id', $id)->first();
        }

        $data['industries'] = \App\Models\JobIndustry::active()->orderBy('name')->get();
        $data['levels']     = \App\Models\JobLevel::active()->orderBy('name')->get();
        $data['provinces']  = Province::orderBy('name')->get();
        $data['wards']      = [];

        if ($data['job']->province_id) {
            $data['wards'] = Ward::where('province_id', $data['job']->province_id)->orderBy('name')->get();
        }

        $data['pageTitle'] = "Edit Job";
        return $data;
    }

    public function storeJob(Request $request, $id, $sellerId = 0)
    {
        $validationRules = $this->getJobValidationRule($id);
        $request->validate($validationRules);

        $job = new Job();

        if ($id) {
            $job = Job::findOrFail($id);

            if ($sellerId && $job->seller_id != $sellerId) {
                $notify[] = ['error', 'This job doesn\'t belong to this seller'];
                return $notify;
            }
        }

        if (!$id) {
            $job->seller_id = $sellerId;
        }
        $job->job_type = $request->job_type ?? 1;
        $job->industry_id = $request->industry_id;
        $job->title = $request->title;
        $job->slug = $request->slug ?? \Illuminate\Support\Str::slug($request->title);

        // Company info
        $job->company_name = $request->company_name;
        if ($request->hasFile('company_logo')) {
            try {
                $job->company_logo = fileUploader($request->company_logo, getFilePath('job'), getFileSize('job'), $job->company_logo);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload company logo: ' . $exp->getMessage()];
                return $notify;
            }
        }

        // Job details
        $job->job_level_id = $request->job_level_id;

        // Location
        $job->work_location = $request->work_location;
        $job->work_address = $request->work_address;
        $job->province_id = $request->province_id;
        $job->ward_id = $request->ward_id;

        // Salary
        $job->salary_type = $request->salary_type ?? 'negotiable';
        $job->salary_from = $request->salary_type === 'range' ? ($request->salary_from ?? 0) : 0;
        $job->salary_to   = $request->salary_type === 'range' ? ($request->salary_to ?? 0) : 0;
        $job->salary_label = $request->salary_label;

        // Dates
        $job->application_deadline = $request->application_deadline;
        $job->job_expires_at = $request->job_expires_at;

        // Description
        $job->description = $request->description;

        // CV File (for seekers)
        if ($request->hasFile('cv_file')) {
            try {
                $job->cv_file = fileUploader($request->cv_file, getFilePath('job_cv'), null, $job->cv_file);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload CV file: ' . $exp->getMessage()];
                return $notify;
            }
        }

        if ($job->job_type == 2) {
            $job->email = $request->email;
            $job->phone = $request->phone;
        }

        // Application method
        $job->application_method = $request->application_method ?? 'platform';
        $job->application_email = $request->application_email;
        $job->application_link = $request->application_link;

        // SEO
        $job->meta_title = $request->meta_title;
        $job->meta_description = $request->meta_description;

        $job->status = Status::ENABLE;
        $job->is_featured = Status::NO;

        $job->save();

        // Multi images
        if ($request->hasFile('images')) {
            foreach ($request->images as $file) {
                try {
                    $jobImage = new JobImage();
                    $jobImage->job_id = $job->id;
                    $jobImage->image = fileUploader($file, getFilePath('job'), getFileSize('job'));
                    $jobImage->save();
                } catch (\Exception $exp) {
                    // Skip or handle
                }
            }
        }

        $message = $id ? 'Job updated successfully' : 'Job added successfully';
        $notify[] = ['success', $message];
        return $notify;
    }

    public function deleteJob($id, $sellerId = 0)
    {
        $query = Job::where('id', $id);
        if ($sellerId) {
            $query = $query->where('seller_id', $sellerId);
        }

        $job = $query->firstOrFail();

        // Delete company logo
        if ($job->company_logo) {
            $location = getFilePath('job');
            fileManager()->removeFile($location . '/' . $job->company_logo);
        }

        // Delete multiple images
        foreach ($job->images as $img) {
            $location = getFilePath('job');
            fileManager()->removeFile($location . '/' . $img->image);
            $img->delete();
        }

        // Delete CV
        if ($job->cv_file) {
            $location = getFilePath('job_cv');
            fileManager()->removeFile($location . '/' . $job->cv_file);
        }

        $job->delete();

        $notify[] = ['success', "Job deleted successfully"];
        return $notify;
    }



    protected function getJobValidationRule($id)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'industry_id' => 'required|integer|exists:job_industries,id',
            'job_level_id' => 'required|integer|exists:job_levels,id',
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png',
            'work_location' => 'nullable|string|max:255',
            'province_id' => 'required|integer|exists:provinces,id',
            'ward_id' => 'required|integer|exists:wards,id',
            'salary_type' => 'nullable|in:range,negotiable',
            'salary_from' => 'nullable|required_if:salary_type,range|numeric|min:0',
            'salary_to' => 'nullable|required_if:salary_type,range|numeric|min:0|gte:salary_from',
            'salary_label' => 'nullable|string|max:100',
            'application_deadline' => 'nullable|date|after_or_equal:today',
            'description' => 'required|string',
            'job_type' => 'required|in:1,2',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:40',
            'cv_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'images' => 'nullable|array',
            'images.*' => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];

        return $rules;
    }

    public function getJobWards(Request $request)
    {
        $provinceId = $request->province_id;
        if (!$provinceId) {
            return response()->json(['wards' => []]);
        }

        $wards = Ward::where('province_id', $provinceId)->orderBy('name')->get();
        return response()->json(['wards' => $wards]);
    }
}