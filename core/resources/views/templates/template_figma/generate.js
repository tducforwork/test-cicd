const fs = require('fs');
const lines = fs.readFileSync('index.html', 'utf8').split('\n');

const header = lines.slice(0, 89).join('\n');
const footer = lines.slice(1132).join('\n');

const aboutContent = `
    <!-- PAGE BREADCRUMB -->
    <div class="breadcrumb-section" style="background-color: #f8f9fa; padding: 20px 0; border-bottom: 1px solid #eaeaea;">
        <div class="container">
            <a href="index.html" style="color: #666; font-size: 14px;">Trang chủ</a>
            <span style="margin: 0 10px; color: #ccc;">/</span>
            <span style="color: var(--primary); font-weight: 600; font-size: 14px;">Giới thiệu</span>
        </div>
    </div>

    <!-- ABOUT HERO -->
    <section class="about-hero" style="padding: 80px 0; background-color: white;">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;">
                <div>
                    <h1 style="font-size: 40px; margin-bottom: 20px; font-weight: 800; color: var(--text-dark);">Về Quảng Phát Logistic</h1>
                    <p style="font-size: 16px; color: #555; line-height: 1.8; margin-bottom: 20px;">
                        Thành lập với tầm nhìn trở thành cầu nối vững chắc cho hoạt động giao thương toàn cầu, <strong>Quảng Phát Logistic</strong> tự hào là một trong những đơn vị tiên phong cung cấp giải pháp vận chuyển xuất nhập khẩu và sàn thương mại điện tử trọn gói.
                    </p>
                    <p style="font-size: 16px; color: #555; line-height: 1.8; margin-bottom: 30px;">
                        Chúng tôi không chỉ là một nhà vận chuyển, mà còn là một đối tác chiến lược đồng hành cùng sự phát triển của hàng ngàn doanh nghiệp Việt Nam trên chặng đường vươn ra biển lớn.
                    </p>
                    <div style="display: flex; gap: 30px;">
                        <div>
                            <h3 style="font-size: 32px; color: var(--primary); margin-bottom: 5px;">10+</h3>
                            <p style="font-size: 14px; color: #777;">Năm kinh nghiệm</p>
                        </div>
                        <div>
                            <h3 style="font-size: 32px; color: var(--primary); margin-bottom: 5px;">5000+</h3>
                            <p style="font-size: 14px; color: #777;">Đối tác tin cậy</p>
                        </div>
                        <div>
                            <h3 style="font-size: 32px; color: var(--primary); margin-bottom: 5px;">100%</h3>
                            <p style="font-size: 14px; color: #777;">Cam kết chất lượng</p>
                        </div>
                    </div>
                </div>
                <div>
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8ed7444ce2?q=80&w=1000&auto=format&fit=crop" alt="Quảng Phát Logistic" style="width: 100%; border-radius: 10px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    </section>

    <!-- CORE VALUES -->
    <section style="padding: 80px 0; background-color: #f8f9fa;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 50px;">
                <span style="color: var(--primary); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; font-size: 12px;">Giá trị cốt lõi</span>
                <h2 style="font-size: 32px; margin-top: 10px;">Tầm Nhìn & Sứ Mệnh</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
                <div style="background: white; padding: 40px 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                    <div style="width: 70px; height: 70px; background-color: rgba(13, 90, 159, 0.1); border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 25px;">
                        <i class="fa-solid fa-eye" style="font-size: 30px; color: var(--primary);"></i>
                    </div>
                    <h3 style="font-size: 20px; margin-bottom: 15px;">Tầm nhìn</h3>
                    <p style="color: #666; line-height: 1.6; font-size: 15px;">Trở thành hệ sinh thái Thương mại điện tử & Logistics số 1 Việt Nam, kết nối liền mạch nhà sản xuất và người tiêu dùng.</p>
                </div>
                
                <div style="background: white; padding: 40px 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                    <div style="width: 70px; height: 70px; background-color: rgba(13, 90, 159, 0.1); border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 25px;">
                        <i class="fa-solid fa-bullseye" style="font-size: 30px; color: var(--primary);"></i>
                    </div>
                    <h3 style="font-size: 20px; margin-bottom: 15px;">Sứ mệnh</h3>
                    <p style="color: #666; line-height: 1.6; font-size: 15px;">Mang đến giải pháp vận chuyển thông minh, tối ưu chi phí và tạo ra những trải nghiệm mua sắm tuyệt vời nhất.</p>
                </div>
                
                <div style="background: white; padding: 40px 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s ease;">
                    <div style="width: 70px; height: 70px; background-color: rgba(13, 90, 159, 0.1); border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 25px;">
                        <i class="fa-solid fa-handshake" style="font-size: 30px; color: var(--primary);"></i>
                    </div>
                    <h3 style="font-size: 20px; margin-bottom: 15px;">Cam kết</h3>
                    <p style="color: #666; line-height: 1.6; font-size: 15px;">Đồng hành cùng đối tác trên tinh thần Tôn trọng - Trách nhiệm - Đổi mới và Hướng tới khách hàng.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- OUR SERVICES -->
    <section style="padding: 80px 0; background-color: white;">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;">
                <div style="order: 2;">
                    <h2 style="font-size: 32px; margin-bottom: 20px; font-weight: 800; color: var(--text-dark);">Dịch Vụ Toàn Diện</h2>
                    <p style="font-size: 16px; color: #555; line-height: 1.8; margin-bottom: 30px;">
                        Hệ thống sinh thái dịch vụ đa dạng được thiết kế chuyên biệt để đáp ứng mọi nhu cầu cung ứng và phân phối.
                    </p>
                    
                    <div style="margin-bottom: 20px; display: flex; gap: 20px;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: #f0f7ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-solid fa-truck-fast" style="color: var(--primary); font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 18px; margin-bottom: 8px;">Vận tải quốc tế & nội địa</h4>
                            <p style="color: #666; font-size: 14px; line-height: 1.5;">Giải pháp vận tải đa phương thức: Đường biển, Đường bộ, Hàng không nhanh chóng và an toàn tuyệt đối.</p>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 20px; display: flex; gap: 20px;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: #f0f7ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-solid fa-warehouse" style="color: var(--primary); font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 18px; margin-bottom: 8px;">Kho bãi & Phân phối</h4>
                            <p style="color: #666; font-size: 14px; line-height: 1.5;">Hệ thống kho diện tích lớn, hiện đại cùng quy trình đóng gói, quản lý tồn kho chuyên nghiệp bằng công nghệ cao.</p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: #f0f7ff; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fa-solid fa-cart-shopping" style="color: var(--primary); font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 18px; margin-bottom: 8px;">Sàn Thương Mại Điện Tử</h4>
                            <p style="color: #666; font-size: 14px; line-height: 1.5;">Nơi cung cấp đa dạng sản phẩm chính hãng với giá tận gốc, trải nghiệm mua sắm thông minh và tích hợp tiện lợi.</p>
                        </div>
                    </div>
                </div>
                <div style="order: 1;">
                    <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1000&auto=format&fit=crop" alt="Dịch vụ Logistics" style="width: 100%; border-radius: 10px; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>
    </section>

    <!-- TEAM SECTION -->
    <section style="padding: 80px 0; background-color: #f8f9fa;">
        <div class="container">
            <div style="text-align: center; margin-bottom: 50px;">
                <h2 style="font-size: 32px; margin-top: 10px;">Đội Ngũ Chuyên Gia</h2>
                <p style="color: #666; margin-top: 15px; max-width: 600px; margin-left: auto; margin-right: auto;">Con người là tài sản quý giá nhất tại Quảng Phát. Đội ngũ của chúng tôi hội tụ những chuyên gia giàu kinh nghiệm, năng động và tận tâm với nghề.</p>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px;">
                <!-- Member 1 -->
                <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
                    <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400&auto=format&fit=crop" alt="Team Member" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 20px;">
                        <h4 style="font-size: 18px; margin-bottom: 5px;">Nguyễn Văn A</h4>
                        <p style="color: var(--primary); font-size: 14px; font-weight: 600; margin-bottom: 10px;">CEO & Founder</p>
                        <p style="color: #777; font-size: 13px;">Chuyên gia chiến lược Logistics & Supply Chain.</p>
                    </div>
                </div>
                <!-- Member 2 -->
                <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400&auto=format&fit=crop" alt="Team Member" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 20px;">
                        <h4 style="font-size: 18px; margin-bottom: 5px;">Trần Thị B</h4>
                        <p style="color: var(--primary); font-size: 14px; font-weight: 600; margin-bottom: 10px;">Giám Đốc Vận Hành</p>
                        <p style="color: #777; font-size: 13px;">Điều phối hệ thống kho bãi và mạng lưới giao nhận.</p>
                    </div>
                </div>
                <!-- Member 3 -->
                <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
                    <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=400&auto=format&fit=crop" alt="Team Member" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 20px;">
                        <h4 style="font-size: 18px; margin-bottom: 5px;">Lê Hoàng C</h4>
                        <p style="color: var(--primary); font-size: 14px; font-weight: 600; margin-bottom: 10px;">Giám Đốc E-Commerce</p>
                        <p style="color: #777; font-size: 13px;">Tối ưu trải nghiệm mua sắm số và hợp tác nhà bán.</p>
                    </div>
                </div>
                <!-- Member 4 -->
                <div style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
                    <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=400&auto=format&fit=crop" alt="Team Member" style="width: 100%; height: 250px; object-fit: cover;">
                    <div style="padding: 20px;">
                        <h4 style="font-size: 18px; margin-bottom: 5px;">Phạm Mỹ D</h4>
                        <p style="color: var(--primary); font-size: 14px; font-weight: 600; margin-bottom: 10px;">Trưởng Phòng CSKH</p>
                        <p style="color: #777; font-size: 13px;">Đảm bảo mức độ hài lòng tuyệt đối của đối tác & khách hàng.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section style="padding: 80px 0; background: linear-gradient(135deg, var(--primary) 0%, #0a3a66 100%); text-align: center;">
        <div class="container">
            <h2 style="color: white; font-size: 36px; margin-bottom: 20px;">Sẵn sàng hợp tác cùng Quảng Phát?</h2>
            <p style="color: rgba(255,255,255,0.8); font-size: 16px; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto;">Hãy liên hệ ngay hôm nay để nhận được báo giá và các giải pháp vận chuyển, bán hàng tối ưu nhất cho doanh nghiệp của bạn.</p>
            <a href="#" class="btn btn-primary" style="background-color: white; color: var(--primary); font-size: 16px; padding: 15px 40px; font-weight: 700; display: inline-block; border-radius: 6px; text-decoration: none;">Liên Hệ Ngay</a>
        </div>
    </section>
`;

fs.writeFileSync('about.html', header + '\n' + aboutContent + '\n' + footer);
console.log('done');
