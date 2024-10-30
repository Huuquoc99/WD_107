
import { Link, useNavigate } from "react-router-dom";

const Header = () => {
  return (
    <div className="bg-gradient-to-r from-[#4E7C32] to-[#abaf98] py-4 px-6">
      <div className="flex justify-between items-center mb-4">
        <div className="flex items-center space-x-4">
          <Link to={""}>
            <div className="opacity-75 hover:opacity-100">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                strokeWidth="1.5"
                stroke="currentColor"
                className="size-8 text-white"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"
                />
              </svg>
            </div>
          </Link>
          {/* <div className="flex items-center">
            <span className="text-white">Trang chủ</span>
          </div> */}
        </div>
  
        <div className="relative flex items-center">
          <form>
            <input
              type="text"
              placeholder="Tìm kiếm sản phẩm, thương hiệu và nhiều hơn nữa"
              // value={searchTerm}
              // onChange={handleSearchChange}
              className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 w-[500px]"
            />
          </form>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth="2"
            stroke="currentColor"
            className="w-5 h-5 absolute left-[470px] top-[10px] cursor-pointer"
          //   onClick={handleSearchClick}
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
            />
          </svg>
        </div>
  
        <div className="flex items-center space-x-6">
          <div className="flex items-center">
            <img
              src="/src/assets/image/icon_user.png"
              alt="Tài khoản"
              className="w-[25px]"
            />
            <a href="/register" className="text-gray-50 hover:text-white pl-1">
              Tài khoản
            </a>
          </div>
          <div className="flex relative">
            {/* <div className="absolute w-3 h-3 rounded-full flex justify-center items-center bg-[#F80808] text-white right-[-8px] top-[-4px]">
              <p className="text-[6px]">3</p>
            </div> */}
            <img
              src="/src/assets/image/icon_cart.png"
              alt="Giỏ hàng"
              className="w-[25px]"
            />
            <a href="#" className="text-gray-50 hover:text-white pl-1">
              Giỏ hàng
            </a>
          </div>
        </div>
      </div>
  
      <div className="border-t border-white w-full max-w-screen-xl mx-auto mb-4"></div>
  
      <div className="flex justify-center items-center space-x-4">
        <div className="flex items-center space-x-6">
          <Link className="text-gray-50 hover:text-white px-4" to="products">
            Cửa hàng
          </Link>
        </div>
        <div className="relative menu-item">
          <a
            href="#"
            className="text-gray-50 hover:text-white px-4 flex justify-center items-end gap-1"
          >
            Hãng điện thoại 
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              strokeWidth="1.5"
              stroke="currentColor"
              className="mb-[1px] size-4"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="m19.5 8.25-7.5 7.5-7.5-7.5"
              />
            </svg>
          </a>
          <ul className="w-[100px] bg-white text-[#665345] absolute left-4 top-6 z-10 hidden">
            <li className="flex gap-2 justify-start items-center px-2 cursor-pointer">
              <div className="w-1 h-1 rounded-full bg-[#665345] dotted-menu"></div>
             Apple
            </li>
            <li className="flex gap-2 justify-start items-center px-2 cursor-pointer">
              <div className="w-1 h-1 rounded-full bg-[#665345] dotted-menu"></div>
             Samsung
            </li>
            <li className="flex gap-2 justify-start items-center px-2 cursor-pointer">
              <div className="w-1 h-1 rounded-full bg-[#665345] dotted-menu"></div>
              Xiaomi
            </li>
          </ul>
        </div>
      </div>
    </div>
  );
  
};

export default Header;
