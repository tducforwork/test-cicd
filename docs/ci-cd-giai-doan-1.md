# Giai đoạn 1: Dựng CI tối thiểu với GitHub Actions

## Mục tiêu

Sau khi hoàn thành giai đoạn này:

- Mỗi lần push lên branch `master`, GitHub Actions tự động kiểm tra dự án.
- Mỗi pull request vào `master` phải chạy qua CI.
- CI cài dependency, kiểm tra `composer.json`, kiểm tra format và chạy PHPUnit.
- Có thể chủ động làm pipeline thất bại, đọc log và sửa để pipeline chạy thành công.
- Test trong giai đoạn này không kết nối database.

> Lưu ý: không chạy `php artisan migrate:fresh` với cấu hình hiện tại. Các migration chưa thể dựng database từ đầu và có thể làm mất dữ liệu MySQL local.

## Bước 1: Tạo branch thực hành

Không làm trực tiếp trên `master`. Từ thư mục root của repository, chạy:

```powershell
git switch -c ci/giai-doan-1
```

Kiểm tra branch hiện tại:

```powershell
git branch --show-current
```

Kết quả mong đợi:

```text
ci/giai-doan-1
```

## Bước 2: Tạo cấu trúc test

PHPUnit hiện được cấu hình để tìm test trong:

```text
core/tests/Unit
core/tests/Feature
```

Tạo hai thư mục này:

```powershell
New-Item -ItemType Directory -Force core/tests/Unit
New-Item -ItemType Directory -Force core/tests/Feature
```

Không dùng `php artisan make:test ExampleTest --unit` trong giai đoạn này.
Project hiện query cache và nhiều bảng database ngay trong
`AppServiceProvider::boot()`. Artisan phải bootstrap application trước khi chạy
command, nên command sẽ thất bại nếu database local chưa đầy đủ.

Tạo file `core/tests/Unit/ExampleTest.php` với nội dung:

```php
<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_ci_can_run_phpunit(): void
    {
        $this->assertSame(4, 2 + 2);
    }
}
```

Đây là unit test thuần PHP. Test không bootstrap Laravel và không kết nối database.

## Bước 3: Chạy PHPUnit tại local

Di chuyển vào thư mục Laravel:

```powershell
Set-Location core
```

Cài dependency nếu thư mục `vendor` chưa tồn tại:

```powershell
composer install
```

Không chạy test qua Artisan trong giai đoạn này. Chạy PHPUnit trực tiếp để unit
test không bootstrap application và không truy cập database:

```powershell
php vendor/bin/phpunit
```

Kết quả mong đợi có một test pass:

```text
PASS  Tests\Unit\ExampleTest
Tests: 1 passed
```

Quay lại root repository:

```powershell
Set-Location ..
```

Nếu PHPUnit cố kết nối database, kiểm tra lại test có đang kế thừa đúng `PHPUnit\Framework\TestCase` hay không.

## Bước 4: Kiểm tra Composer và format tại local

Chạy kiểm tra cấu hình Composer:

```powershell
Set-Location core
composer validate --no-check-publish
```

Chạy Laravel Pint ở chế độ chỉ kiểm tra:

```powershell
vendor/bin/pint --test
```

Trên PowerShell, nếu lệnh trên không chạy được, dùng:

```powershell
php vendor/bin/pint --test
```

`--test` chỉ báo lỗi format, không tự sửa file. Nếu Pint báo rất nhiều lỗi có sẵn của dự án, tạm thời giới hạn kiểm tra vào thư mục test:

```powershell
php vendor/bin/pint --test tests
```

Lệnh dùng trong CI phải giống lệnh chạy thành công tại local.

Quay lại root repository:

```powershell
Set-Location ..
```

## Bước 5: Tạo GitHub Actions workflow

Tạo thư mục:

```powershell
New-Item -ItemType Directory -Force .github/workflows
```

Tạo file `.github/workflows/ci.yml`:

```yaml
name: CI

on:
  push:
    branches:
      - master
  pull_request:
    branches:
      - master

jobs:
  test:
    runs-on: ubuntu-latest

    defaults:
      run:
        working-directory: core

    steps:
      - name: Checkout source code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: "8.2"
          extensions: mbstring
          coverage: none

      - name: Validate composer.json
        run: composer validate --no-check-publish

      - name: Install dependencies
        run: composer install --no-interaction --prefer-dist --no-progress

      - name: Check test code style
        run: php vendor/bin/pint --test tests

      - name: Run tests
        run: php vendor/bin/phpunit
```

Các điểm cần hiểu:

- `on.push`: chạy CI khi code được push lên `master`.
- `on.pull_request`: chạy CI khi pull request nhắm vào `master`.
- `runs-on`: GitHub cấp một máy Ubuntu tạm thời để chạy job.
- `working-directory: core`: tất cả lệnh `run` được chạy trong thư mục Laravel.
- `actions/checkout`: tải source code vào runner.
- `setup-php`: cài PHP 8.2.
- `composer install`: cài dependency theo `composer.lock`.
- Pint và PHPUnit làm pipeline thất bại nếu phát hiện lỗi.

Workflow không cần `.env`, `APP_KEY` hoặc database vì test hiện tại là unit test thuần PHP.

## Bước 6: Commit và push branch

Kiểm tra thay đổi:

```powershell
git status
```

Các file mới dự kiến:

```text
.github/workflows/ci.yml
core/tests/Unit/ExampleTest.php
docs/ci-cd-giai-doan-1.md
```

Commit:

```powershell
git add .github/workflows/ci.yml core/tests/Unit/ExampleTest.php docs/ci-cd-giai-doan-1.md
git commit -m "Add initial GitHub Actions CI"
```

Push branch:

```powershell
git push -u origin ci/giai-doan-1
```

## Bước 7: Tạo pull request và đọc kết quả CI

Trên GitHub:

1. Mở repository `tducforwork/test-cicd`.
2. Tạo pull request từ `ci/giai-doan-1` vào `master`.
3. Mở tab **Checks** hoặc phần kiểm tra ở cuối pull request.
4. Mở job `test` và đọc log từng step.
5. Chỉ merge khi tất cả step đều xanh.

Nếu pipeline đỏ:

1. Mở step bị lỗi.
2. Đọc command, thông báo lỗi và file liên quan.
3. Chạy lại chính command đó tại local trong thư mục `core`.
4. Sửa lỗi, commit và push lại branch.
5. GitHub Actions tự động chạy lại.

## Bước 8: Chủ động tạo một pipeline thất bại

Đổi assertion trong `core/tests/Unit/ExampleTest.php` thành:

```php
$this->assertSame(5, 2 + 2);
```

Commit và push:

```powershell
git add core/tests/Unit/ExampleTest.php
git commit -m "Practice a failing CI test"
git push
```

Quan sát pipeline chuyển đỏ và tìm thông báo PHPUnit cho biết giá trị mong đợi là `5`, nhưng thực tế là `4`.

Sau đó sửa assertion trở lại:

```php
$this->assertSame(4, 2 + 2);
```

Commit và push lần nữa:

```powershell
git add core/tests/Unit/ExampleTest.php
git commit -m "Fix failing CI test"
git push
```

Pipeline phải trở lại màu xanh.

## Bước 9: Bật branch protection

Chỉ thực hiện sau khi workflow đã chạy thành công ít nhất một lần.

Trên GitHub:

1. Vào **Settings** của repository.
2. Mở **Branches** hoặc **Rules > Rulesets**.
3. Tạo rule áp dụng cho branch `master`.
4. Bật yêu cầu pull request trước khi merge.
5. Bật yêu cầu status checks phải pass.
6. Chọn status check của workflow CI.
7. Lưu rule.

Sau bước này, code làm CI thất bại sẽ không được merge vào `master`.

## Checklist hoàn thành

- [ ] Có branch thực hành riêng.
- [ ] `php vendor/bin/phpunit` chạy pass tại local mà không kết nối database.
- [ ] Composer validation chạy pass.
- [ ] Pint kiểm tra thư mục test chạy pass.
- [ ] Workflow CI chạy khi mở pull request vào `master`.
- [ ] Đã thử làm pipeline đỏ và tự đọc log để sửa.
- [ ] Pipeline trở lại xanh sau khi sửa test.
- [ ] Branch `master` được bảo vệ bằng required status check.
