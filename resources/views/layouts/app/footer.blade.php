<footer id="contact" class="ftco-footer ftco-section img">
    <div class="overlay"></div>

    <div class="container">

        <div class="row mb-5">

            <!-- About -->
            <div class="col-md-3">

                <div class="ftco-footer-widget mb-4">

                    <h2 class="ftco-heading-2">ZenStyle</h2>

                    <p>
                        Hair salon &amp; beauty spa - a relaxing space with dedicated service, 
                        accompanying your beauty every day.
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

            <!-- Featured Services -->
            <div class="col-md-4">

                <div class="ftco-footer-widget mb-4">

                    <h2 class="ftco-heading-2">Featured Services</h2>

                    <ul class="list-unstyled">

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Haircut &amp; Styling
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Hair Coloring &amp; Perming
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Facial Care
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Relaxing Massage
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

            <!-- Quick Links -->
            <div class="col-md-2">

                <div class="ftco-footer-widget mb-4 ml-md-4">

                    <h2 class="ftco-heading-2">Quick Links</h2>

                    <ul class="list-unstyled">

                        <li>
                            <a href="{{ route('home') }}" class="py-2 d-block">
                                Home
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#about' : route('home').'#about' }}" class="py-2 d-block">
                                About Us
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="py-2 d-block">
                                Services
                            </a>
                        </li>

                        <li>
                            <a href="{{ request()->routeIs('home') ? '#booking' : route('home').'#booking' }}" class="py-2 d-block">
                                Book Appointment
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('register') }}" class="py-2 d-block">
                                Register
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

            <!-- Contact -->
            <div class="col-md-3">

                <div class="ftco-footer-widget mb-4">

                    <h2 class="ftco-heading-2">
                        Contact Us
                    </h2>

                    <div class="block-23 mb-3">

                        <ul>

                            <li>
                                <span class="icon icon-map-marker"></span>

                                <span class="text">
                                    123 Nguyen Hue Street, District 1,
                                    Ho Chi Minh City, Vietnam
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