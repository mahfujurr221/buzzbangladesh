<div id="top-nav" class="top-nav style-one md:h-[44px] h-[30px]" style="background-color: #9A0002;">
            <div class="container mx-auto h-full">
                <div class="top-nav-main flex justify-between max-md:justify-center h-full">
                    <div class="left-content flex items-center gap-5 max-md:hidden">
                        
                        <div class="choose-type choose-currency flex items-center gap-1.5">
                            <div class="select relative">
                                <p class="selected caption2 text-white">BDT</p>
                                <ul class="list-option bg-white">
                                    <li data-item="BDT" class="caption2 active">BDT</li>
                                </ul>
                            </div>
                            <i class="ph ph-caret-down text-xs text-white"></i>
                        </div>
                    </div>
                    <div class="text-center text-button-uppercase text-white flex items-center">New customers save 10% with the code GET10</div>
                    <div class="right-content flex items-center gap-5 max-md:hidden">
                        @if($setting?->facebook)
                        <a href="{{ $setting?->facebook }}" target="_blank">
                            <i class="icon-facebook text-white"></i>
                        </a>
                        @endif
                        @if($setting?->instagram)
                        <a href="{{ $setting?->instagram }}" target="_blank">
                            <i class="icon-instagram text-white"></i>
                        </a>
                        @endif
                        @if($setting?->youtube)
                        <a href="{{ $setting?->youtube }}" target="_blank">
                            <i class="icon-youtube text-white"></i>
                        </a>
                        @endif
                        @if($setting?->twitter)
                        <a href="{{ $setting?->twitter }}" target="_blank">
                            <i class="icon-twitter text-white"></i>
                        </a>
                        @endif
                        @if($setting?->pinterest)
                        <a href="{{ $setting?->pinterest }}" target="_blank">
                            <i class="icon-pinterest text-white"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>