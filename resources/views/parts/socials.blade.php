<a href="{{config("socials.instagram")}}" target="_blank"
   class="{{!empty($linkClasses) ? implode(" ", $linkClasses) : "" }}">{!! svgIcon("icon/socials/instagram.svg", ['class' => !empty($iconClasses) ? $iconClasses : [] ] ) !!}</a>
<a href="{{config("socials.facebook")}}" target="_blank"
   class="{{!empty($linkClasses) ? implode(" ", $linkClasses) : "" }}">{!! svgIcon("icon/socials/facebook.svg", ['class' => !empty($iconClasses) ? $iconClasses : [] ] ) !!}</a>
<a href="{{config("socials.tiktok")}}" target="_blank"
   class="{{!empty($linkClasses) ? implode(" ", $linkClasses) : "" }}">{!! svgIcon("icon/socials/tiktok.svg", ['class' => !empty($iconClasses) ? $iconClasses : [] ] ) !!}</a>
<a href="{{config("socials.youtube")}}" target="_blank"
   class="{{!empty($linkClasses) ? implode(" ", $linkClasses) : "" }}">{!! svgIcon("icon/socials/youtube.svg", ['class' => !empty($iconClasses) ? $iconClasses : [] ] ) !!}</a>
