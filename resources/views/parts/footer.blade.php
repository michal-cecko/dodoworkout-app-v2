<footer class="border-t border-[#EEE5E5] pt-14 mt-auto bg-white max-lg:pt-10">
    <div class="container-wrapper flex gap-24 mb-10 max-lg:flex-col max-lg:gap-10">
        <div class="relative">
            <div
                class="logo-container mb-7">{!! svgIcon("logo/logo-black-red.svg", ['class' => ['h-[46px] w-[110px] max-lg:h-[34px] max-lg:w-[81px]']]) !!}</div>
            <a class="flex items-center gap-3 mb-4" href="mailto:dominikklimek07@gmail.com">
                {!! svgIcon("icon/mail.svg") !!}
                info@dodoworkout.com
            </a>
            <a class="flex items-center gap-3 mb-8" href="tel:+421 911 266 631">
                {!! svgIcon("icon/phone.svg", ['class' => ['text-primary']]) !!}
                +421 950 451 310
            </a>
            <div class="flex gap-6 items-center text-[#373737]">
                @include('parts.socials')
            </div>
            {!! svgIcon("svg/striped-circle.svg", ['class' => ['text-[#F4F4F4] absolute z-[-1] right-0 top-[10%]']]) !!}
        </div>
        <div class="grid grid-cols-3 gap-40 w-full max-lg:grid-cols-1 max-lg:gap-10">
            <div>
                <h5 class="mb-5 uppercase text-primary font-bold">{{__("footer_about_heading")}}</h5>
                <p class="max-w-[181px]">
                    {{__("footer_about_content")}}
                    <a href="{{\App\Models\Post::find(12)->permalink}}" class="block font-bold text-primary">{{__("read_more")}}</a>
                    <br/>
                    {{__("business_id")}}: 56841337
                </p>
            </div>
           {{-- <div>
                <h5 class="mb-5 uppercase text-primary font-bold">{{__("footer_links_heading")}}</h5>
                <ul class="flex flex-col gap-3">
                    <li><a href="{{ LocaleService::localizePath("/blog") }}">{{__("header_blog")}}</a></li>
                    --}}{{--<li><a href="#">Personal / online training</a></li>--}}{{--
                    <li><a href="{{ LocaleService::localizePath("/events") }}">{{__("header_events")}}</a></li>
                    --}}{{--<li><a href="#">Shop</a></li>--}}{{--
                    --}}{{--<li><a href="#">About</a></li>--}}{{--
                    --}}{{--<li><a href="#">Contact</a></li>--}}{{--
                </ul>
            </div>--}}
            {{--<div>
                <h5 class="mb-5 uppercase text-primary font-bold">{{__("footer_info_heading")}}</h5>
                <ul class="flex flex-col gap-3">
                    <li><a href="#">Return policy</a></li>
                    <li><a href="#">Privacy policy</a></li>
                    <li><a href="#">Business conditions</a></li>
                </ul>
            </div>--}}
        </div>
    </div>
    <div class="bg-[#F8F8F8] border-t border-[#EEE5E5] h-16 flex items-center justify-center text-sm px-sm-0 px-6 text-center">
        Copyright © 2025 | DODOWORKOUT | {{__("all_rights_reserved")}} | <a style="display: inline-block; margin-left: 0.2rem" href="https://cecko.dev" target="_blank" rel="noopener">{{__("made_by")}} <b>Michal Čečko</b></a>
    </div>
</footer>
