<footer id="contact" class="ftco-footer ftco-section img">
    <div class="overlay"></div>

    <div class="container">

        <div class="row mb-5">

            <!-- About -->
            <div class="col-md-3">

                <div class="ftco-footer-widget mb-4">

                    <h2 class="ftco-heading-2">ZenStyle</h2>

                    <p>
                        Salon tóc &amp; spa chăm sóc sắc đẹp - không gian thư giãn, dịch vụ tận tâm,
                        đồng hành cùng vẻ đẹp của bạn mỗi ngày.
                    </p>

                    <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">

                        <li class="ftco-animate">
                            <a href="#"><span class="icon-facebook"></span></a>
                        </li>

                        <li class="ftco-animate">
                            <a href="#"><span class="icon-instagram"></span></a>
                        </li>

                        <li class="ftco-animate">
                            <a href="#"><span class="icon-youtube"></span></a>
                        </li>

                    </ul>

                </div>

            </div>

            <!-- Dịch vụ nổi bật -->
            <div class="col-md-4">

                <div class="ftco-footer-widget mb-4">

                    <h2 class="ftco-heading-2">Dịch Vụ Nổi Bật</h2>

                    <ul class="list-unstyled">

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Cắt tóc tạo kiểu
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Nhuộm / Uốn tóc
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Chăm sóc da mặt
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Massage thư giãn
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

            <!-- Liên kết nhanh -->
            <div class="col-md-2">

                <div class="ftco-footer-widget mb-4 ml-md-4">

                    <h2 class="ftco-heading-2">Liên Kết</h2>

                    <ul class="list-unstyled">

                        <li>
                            <a href="{{ route('home') }}" class="py-2 d-block">
                                Trang chủ
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#about' : route('home').'#about' }}" class="py-2 d-block">
                                Giới thiệu
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Dịch vụ
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#booking' : route('home').'#booking' }}" class="py-2 d-block">
                                Đặt lịch
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('register') }}" class="py-2 d-block">
                                Đăng ký
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

            <!-- Contact -->
            <div class="col-md-3">

                <div class="ftco-footer-widget mb-4">

                    <h2 class="ftco-heading-2">
                        Liên Hệ
                    </h2>

                    <div class="block-23 mb-3">

                        <ul>

                            <li>
                                <span class="icon icon-map-marker"></span>

                                <span class="text">
                                    123 Đường Nguyễn Huệ, Quận 1,
                                    TP. Hồ Chí Minh, Việt Nam
                                </span>
                            </li>

                            <li>
                                <a href="tel:0909123456">
                                    <span class="icon icon-phone"></span>
                                    <span class="text">
                                        0909 123 456
                                    </span>
                                </a>
                            </li>

                            <li>
                                <a href="mailto:info@zenstyle.vn">
                                    <span class="icon icon-envelope"></span>
                                    <span class="text">
                                        info@zenstyle.vn
                                    </span>
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12 text-center">

                <p>
                    ZenStyle. All rights reserved.
                </p>

            </div>

        </div>

    </div>

</footer>