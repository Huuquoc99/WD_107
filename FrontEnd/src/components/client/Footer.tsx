import React from "react";

const Footer = () => {
    return (
        <>
          <div className="bg-[#053D29] text-white w-full h-auto py-10">
            <div className="grid grid-cols-7 w-full max-w-[1300px] mx-auto px-4 text-[13px]">
              <div className="col-span-2 mt-14">
                <p className="my-1">Sản phẩm chính hãng 2024</p>
                <p>Thiết kế hiện đại, trẻ trung và năng động</p>
                <p>Chúng tôi luôn đồng hành cùng bạn</p>
                <div className="flex justify-around w-1/3 h-4 mt-7 space-x-4">
                  <img src="/src/assets/image/fb.png" alt="Facebook" />
                  <img src="/src/assets/image/twitter.png" alt="Twitter" />
                  <img src="/src/assets/image/ins.png" alt="Instagram" />
                  <img src="/src/assets/image/yt.png" alt="YouTube" />
                  <img src="/src/assets/image/mdi_instagram.png" alt="Instagram" />
                </div>
              </div>
              <div className="col-span-2">
                <p className="font-bold text-xl text-[#F9F3EE]">Về chúng tôi</p>
                <ul>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Liên hệ chúng tôi</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Giới thiệu</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Nghề nghiệp</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Thông tin công ty</li>
                </ul>
              </div>
              <div className="col-span-2">
                <p className="font-bold text-xl text-[#F9F3EE]">Hỗ trợ</p>
                <ul>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Các nhà sản xuất của chúng tôi</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Thanh toán</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Giao hàng</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Hủy đơn hàng & Đổi trả</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Báo cáo vi phạm</li>
                </ul>
              </div>
              <div className="col-span-1">
                <p className="font-bold text-xl text-[#F9F3EE]">Chính sách</p>
                <ul>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Chính sách đổi trả</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Điều khoản sử dụng</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">An ninh</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Quyền riêng tư</li>
                  <li className="text-[#F9F3EE] hover:opacity-60 cursor-pointer py-2">Mục lục trang</li>
                </ul>
              </div>
            </div>
          </div>
      
          <div className="flex items-center justify-between bg-[#062F21] h-16 text-white px-4 md:px-[220px]">
            <p className="text-sm">2023 hood.de, Inc.</p>
            <img
              src="/src/assets/image/icons_payment 1.png"
              alt="Thanh toán"
              className="h-6"
            />
            <p className="text-sm">Tập đoàn TKT - Việt Nam</p>
          </div>
        </>
      );
      
};

export default Footer;
