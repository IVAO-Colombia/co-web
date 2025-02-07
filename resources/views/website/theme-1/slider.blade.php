   <!-- Revolution Slider -->
   <section id="slider">
       <div id="rev_slider_24_1_wrapper" class="rev_slider_wrapper fullscreen-container" data-alias="website-intro"
           data-source="gallery" style="background:#000000;padding:0px;">
           <!-- START REVOLUTION SLIDER 5.4.1 fullscreen mode -->
           <div id="rev_slider_24_1" class="rev_slider fullscreenbanner tiny_bullet_slider" style="display:none;"
               data-version="5.4.1">
               <ul>
                   @foreach ($sliders as $item)
                       <!-- SLIDE  -->
                       <li data-index="rs-67{{ $item->id }}" data-transition="fade" data-slotamount="default"
                           data-hideafterloop="0" data-hideslideonmobile="off" data-easein="default"
                           data-easeout="default" data-masterspeed="600"
                           data-thumb="{{ asset('storage/sliders/' . $item->image) }}" data-rotate="0"
                           data-saveperformance="off" data-title="{{ $item->title }}" data-param1="" data-param2=""
                           data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8=""
                           data-param9="" data-param10="" data-description=""
                           data-slicey_shadow="0px 0px 0px 0px transparent">
                           <!-- MAIN IMAGE -->
                           <img src="{{ asset('storage/sliders/' . $item->image) }}" alt="{{ $item->title }}"
                               data-bgposition="center center" data-kenburns="on" data-duration="5000"
                               data-ease="Power2.easeInOut" data-scalestart="100" data-scaleend="150"
                               data-rotatestart="0" data-rotateend="0" data-blurstart="20" data-blurend="0"
                               data-offsetstart="0 0" data-offsetend="0 0" class="rev-slidebg" data-no-retina>
                           <!-- LAYERS -->

                           {{-- <!-- BACKGROUND VIDEO LAYER -->
                           <div class="rs-background-video-layer" data-volume="mute" data-videowidth="100%"
                               data-duration="14000" data-videoheight="100%"
                               data-videomp4="{{ asset('video/video-1.mp4') }}" data-videopreload="auto"
                               data-videoloop="loop" data-forceCover="1" data-aspectratio="16:9" data-autoplay="true"
                               data-autoplayonlyfirsttime="false"></div> --}}


                           <!-- LAYER NR. 1 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}9" data-x="['center','center','center','center']"
                               data-hoffset="['-112','-43','-81','44']" data-y="['middle','middle','middle','middle']"
                               data-voffset="['-219','-184','-185','182']" data-width="['250','250','150','150']"
                               data-height="['150','150','100','100']" data-whitespace="nowrap" data-type="shape"
                               data-slicey_offset="250" data-slicey_blurstart="0" data-slicey_blurend="20"
                               data-responsive_offset="on"
                               data-frames='[{"delay":300,"speed":1000,"frame":"0","from":"rX:0deg;rY:0deg;rZ:0deg;sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3700","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                               style="z-index: 5;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 2 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}10" data-x="['center','center','center','center']"
                               data-hoffset="['151','228','224','117']" data-y="['middle','middle','middle','middle']"
                               data-voffset="['-212','-159','71','-222']" data-width="['150','150','100','100']"
                               data-height="['200','150','150','150']" data-whitespace="nowrap" data-type="shape"
                               data-slicey_offset="250" data-slicey_blurstart="0" data-slicey_blurend="20"
                               data-responsive_offset="on"
                               data-frames='[{"delay":350,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3650","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                               style="z-index: 6;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 3 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}29" data-x="['center','center','center','center']"
                               data-hoffset="['339','-442','104','-159']" data-y="['middle','middle','middle','middle']"
                               data-voffset="['2','165','-172','219']" data-width="['250','250','150','150']"
                               data-height="['150','150','100','100']" data-whitespace="nowrap" data-type="shape"
                               data-slicey_offset="250" data-slicey_blurstart="0" data-slicey_blurend="20"
                               data-responsive_offset="on"
                               data-frames='[{"delay":400,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3600","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 7;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 4 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}12"
                               data-x="['center','center','center','center']"
                               data-hoffset="['162','216','-239','193']"
                               data-y="['middle','middle','middle','middle']" data-voffset="['195','245','6','146']"
                               data-width="['250','250','100','100']" data-height="150" data-whitespace="nowrap"
                               data-type="shape" data-slicey_offset="250" data-slicey_blurstart="0"
                               data-slicey_blurend="20" data-responsive_offset="on"
                               data-frames='[{"delay":450,"speed":1000,"frame":"0","from":"opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3550","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 8;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 5 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}34"
                               data-x="['center','center','center','center']"
                               data-hoffset="['-186','-119','273','-223']"
                               data-y="['middle','middle','middle','middle']" data-voffset="['269','217','-121','69']"
                               data-width="['300','300','150','150']" data-height="['200','200','150','150']"
                               data-whitespace="nowrap" data-type="shape" data-slicey_offset="250"
                               data-slicey_blurstart="0" data-slicey_blurend="20" data-responsive_offset="on"
                               data-frames='[{"delay":500,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3500","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 9;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 6 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}11"
                               data-x="['center','center','center','center']"
                               data-hoffset="['-325','292','162','-34']"
                               data-y="['middle','middle','middle','middle']" data-voffset="['3','55','-275','-174']"
                               data-width="150" data-height="['250','150','50','50']" data-whitespace="nowrap"
                               data-type="shape" data-slicey_offset="250" data-slicey_blurstart="0"
                               data-slicey_blurend="20" data-responsive_offset="on"
                               data-frames='[{"delay":550,"speed":1000,"frame":"0","from":"opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3450","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 10;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 7 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}27"
                               data-x="['center','center','center','center']"
                               data-hoffset="['-429','523','-190','-306']"
                               data-y="['middle','middle','middle','middle']"
                               data-voffset="['-327','173','181','480']" data-width="['250','250','150','150']"
                               data-height="['300','300','150','150']" data-whitespace="nowrap" data-type="shape"
                               data-slicey_offset="300" data-slicey_blurstart="0" data-slicey_blurend="20"
                               data-responsive_offset="on"
                               data-frames='[{"delay":320,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3680","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 11;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 8 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}28"
                               data-x="['center','center','center','center']"
                               data-hoffset="['422','-409','208','225']"
                               data-y="['middle','middle','middle','middle']"
                               data-voffset="['-245','-72','294','-14']" data-width="['300','300','150','150']"
                               data-height="['250','250','100','100']" data-whitespace="nowrap" data-type="shape"
                               data-slicey_offset="300" data-slicey_blurstart="0" data-slicey_blurend="20"
                               data-responsive_offset="on"
                               data-frames='[{"delay":360,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3640","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 12;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 9 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}30"
                               data-x="['center','center','center','center']" data-hoffset="['549','-445','28','58']"
                               data-y="['middle','middle','middle','middle']" data-voffset="['236','400','316','287']"
                               data-width="['300','300','150','200']" data-height="['250','250','150','50']"
                               data-whitespace="nowrap" data-type="shape" data-slicey_offset="300"
                               data-slicey_blurstart="0" data-slicey_blurend="20" data-responsive_offset="on"
                               data-frames='[{"delay":400,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3600","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 13;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 10 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}31"
                               data-x="['center','center','center','center']"
                               data-hoffset="['-522','492','-151','262']"
                               data-y="['middle','middle','middle','middle']"
                               data-voffset="['339','-180','330','-141']" data-width="['300','300','150','150']"
                               data-height="['250','250','100','100']" data-whitespace="nowrap" data-type="shape"
                               data-slicey_offset="300" data-slicey_blurstart="0" data-slicey_blurend="20"
                               data-responsive_offset="on"
                               data-frames='[{"delay":440,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3560","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 14;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 11 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}32"
                               data-x="['center','center','center','center']"
                               data-hoffset="['-588','-375','-253','-207']"
                               data-y="['middle','middle','middle','middle']"
                               data-voffset="['72','-328','-172','-111']" data-width="['300','300','150','150']"
                               data-height="['200','200','150','150']" data-whitespace="nowrap" data-type="shape"
                               data-slicey_offset="300" data-slicey_blurstart="0" data-slicey_blurend="20"
                               data-responsive_offset="on"
                               data-frames='[{"delay":480,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3520","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 15;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 12 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}33"
                               data-x="['center','center','center','center']" data-hoffset="['-37','73','-76','-100']"
                               data-y="['middle','middle','middle','middle']"
                               data-voffset="['-401','-340','-293','-246']" data-width="['450','400','250','250']"
                               data-height="['100','100','50','50']" data-whitespace="nowrap" data-type="shape"
                               data-slicey_offset="250" data-slicey_blurstart="0" data-slicey_blurend="20"
                               data-responsive_offset="on"
                               data-frames='[{"delay":310,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3690","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 16;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 13 -->
                           <div class="tp-caption tp-shape tp-shapewrapper tp-slicey  tp-resizeme"
                               id="slide-67-layer-{{ $item->id }}35"
                               data-x="['center','center','center','center']" data-hoffset="['186','38','116','17']"
                               data-y="['middle','middle','middle','middle']" data-voffset="['363','402','190','395']"
                               data-width="['350','400','250','250']" data-height="['100','100','50','50']"
                               data-whitespace="nowrap" data-type="shape" data-slicey_offset="250"
                               data-slicey_blurstart="0" data-slicey_blurend="20" data-responsive_offset="on"
                               data-frames='[{"delay":340,"speed":1000,"frame":"0","from":"sX:1;sY:1;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"+3660","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 17;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 14 -->
                           <div class="tp-caption tp-shape tp-shapewrapper " id="slide-67-layer-{{ $item->id }}1"
                               data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                               data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']"
                               data-width="full" data-height="full" data-whitespace="nowrap" data-type="shape"
                               data-basealign="slide" data-responsive_offset="off" data-responsive="off"
                               data-frames='[{"delay":10,"speed":500,"frame":"0","from":"opacity:0;","to":"o:1;","ease":"Power4.easeOut"},{"delay":"wait","speed":500,"frame":"999","to":"opacity:0;","ease":"Power4.easeOut"}]'
                               data-textAlign="['inherit','inherit','inherit','inherit']" data-paddingtop="[0,0,0,0]"
                               data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                               data-paddingleft="[0,0,0,0]" style="z-index: 18;background-color:rgba(0, 0, 0, 0.5);">
                           </div>
                           <!-- LAYER NR. 15 -->
                           @if ($item->title)
                               <div class="tp-caption   tp-resizeme" id="slide-67-layer-{{ $item->id }}2"
                                   data-x="['center','center','center','center']" data-hoffset="['1','1','0','0']"
                                   data-y="['middle','middle','middle','middle']"
                                   data-voffset="['-70','-70','-70','-70']" data-fontsize="['90','90','70','50']"
                                   data-lineheight="['90','90','70','50']" data-width="['none','none','481','360']"
                                   data-height="none" data-whitespace="['nowrap','nowrap','normal','normal']"
                                   data-type="text" data-responsive_offset="on"
                                   data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:0.9;sY:0.9;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":500,"frame":"999","to":"sX:0.9;sY:0.9;opacity:0;fb:20px;","ease":"Power3.easeInOut"}]'
                                   data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]"
                                   data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                   data-paddingleft="[0,0,0,0]"
                                   style="z-index: 19; white-space: nowrap; font-size: 90px; line-height: 90px; font-weight: 500; color: #ffffff; letter-spacing: -5px;font-family:Rubik;">
                                   {!! $item->title !!} </div>
                           @endif
                           @if ($item->description)
                               <!-- LAYER NR. 16 -->
                               <div class="tp-caption   tp-resizeme" id="slide-67-layer-{{ $item->id }}3"
                                   data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                   data-y="['middle','middle','middle','middle']" data-voffset="['90','90','60','30']"
                                   data-fontsize="['25','25','25','20']" data-lineheight="['35','35','35','30']"
                                   data-width="['480','480','480','360']" data-height="none" data-whitespace="normal"
                                   data-type="text" data-responsive_offset="on"
                                   data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:0.9;sY:0.9;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":500,"frame":"999","to":"sX:0.9;sY:0.9;opacity:0;fb:20px;","ease":"Power3.easeInOut"}]'
                                   data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]"
                                   data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]"
                                   data-paddingleft="[0,0,0,0]"
                                   style="z-index: 20; min-width: 480px; max-width: 480px; white-space: normal; font-size: 25px; line-height: 35px; font-weight: 400; color: #ffffff; letter-spacing: 0px;font-family:Rubik;">
                                   {{ $item->description }} </div>
                           @endif

                           <!-- LAYER NR. 17 -->
                           @if ($item->btn_text)
                               <a class="tp-caption rev-btn  tp-resizeme text-uppercase" href="{{ $item->btn_url }}"
                                   target="_blank" id="slide-67-layer-{{ $item->id }}7"
                                   data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']"
                                   data-y="['middle','middle','middle','middle']"
                                   data-voffset="['200','200','160','120']" data-width="250" data-height="none"
                                   data-whitespace="nowrap" data-type="button" data-actions=''
                                   data-responsive_offset="on"
                                   data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:0.9;sY:0.9;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":500,"frame":"999","to":"sX:0.9;sY:0.9;opacity:0;fb:20px;","ease":"Power3.easeInOut"},{"frame":"hover","speed":"0","ease":"Linear.easeNone","to":"o:1;rX:0;rY:0;rZ:0;z:0;fb:0;","style":"c:rgba(255,255,255,1);bs:solid;bw:0 0 0 0;"}]'
                                   data-textAlign="['center','center','center','center']" data-paddingtop="[0,0,0,0]"
                                   data-paddingright="[50,50,50,50]" data-paddingbottom="[0,0,0,0]"
                                   data-paddingleft="[50,50,50,50]"
                                   style="z-index: 21; min-width: 250px; max-width: 250px; white-space: nowrap; font-size: 18px; line-height: 60px; font-weight: 700; color: rgba(255,255,255,1); letter-spacing: px;font-family:Rubik;background-color:rgb(4, 54, 118);border-color:rgba(0,0,0,1);border-radius:30px 30px 30px 30px;outline:none;box-shadow:none;box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;cursor:pointer;text-decoration: none;">
                                   {{ $item->btn_text }} </a>
                           @endif

                       </li>
                   @endforeach

               </ul>
               <div class="tp-bannertimer tp-bottom" style="height: 5px; background: rgb(4, 54, 118);"></div>
           </div>
       </div>
       <!-- END REVOLUTION SLIDER -->

   </section>
   <!-- end: Revolution Slider-->
   @push('scripts')
       <link href="https://fonts.googleapis.com/css?family=Rubik:500%2C400%2C700" rel="stylesheet" property="stylesheet"
           type="text/css" media="all">
       <link rel="stylesheet" type="text/css"
           href="{{ asset('theme-1/plugins/slider-revolution/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}">
       <link rel="stylesheet" type="text/css"
           href="{{ asset('theme-1/plugins/slider-revolution/fonts/font-awesome/css/font-awesome.css') }}">
       <link rel="stylesheet" type="text/css" href="{{ asset('theme-1/plugins/slider-revolution/css/settings.css') }}">
       <style type="text/css">
           .tiny_bullet_slider .tp-bullet:before {
               content: " ";
               position: absolute;
               width: 100%;
               height: 25px;
               top: -12px;
               left: 0px;
               background: transparent
           }
       </style>
       <style type="text/css">
           .bullet-bar.tp-bullets {}

           .bullet-bar.tp-bullets:before {
               content: " ";
               position: absolute;
               width: 100%;
               height: 100%;
               background: transparent;
               padding: 10px;
               margin-left: -10px;
               margin-top: -10px;
               box-sizing: content-box
           }

           .bullet-bar .tp-bullet {
               width: 60px;
               height: 3px;
               position: absolute;
               background: #aaa;
               background: rgba(204, 204, 204, 0.5);
               cursor: pointer;
               box-sizing: content-box
           }

           .bullet-bar .tp-bullet:hover,
           .bullet-bar .tp-bullet.selected {
               background: rgba(204, 204, 204, 1)
           }

           .bullet-bar .tp-bullet-image {}

           .bullet-bar .tp-bullet-title {}
       </style>
       <!-- REVOLUTION JS FILES -->
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/jquery.themepunch.tools.min.js') }}"></script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/jquery.themepunch.revolution.min.js') }}"></script>
       <!-- SLICEY ADD-ON FILES -->
       <script type='text/javascript'
           src='{{ asset('theme-1/plugins/slider-revolution/revolution-addons/slicey/js/revolution.addon.slicey.min.js?ver=1.0.0') }}'>
       </script>
       <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.actions.min.js') }}"></script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.carousel.min.js') }}"></script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.layeranimation.min.js') }}">
       </script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.migration.min.js') }}">
       </script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.navigation.min.js') }}">
       </script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.parallax.min.js') }}"></script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.slideanims.min.js') }}">
       </script>
       <script type="text/javascript"
           src="{{ asset('theme-1/plugins/slider-revolution/js/extensions/revolution.extension.video.min.js') }}"></script>
       <script type="text/javascript">
           var tpj = jQuery;
           var revapi24;
           tpj(document).ready(function() {
               if (tpj("#rev_slider_24_1").revolution == undefined) {
                   revslider_showDoubleJqueryError("#rev_slider_24_1");
               } else {
                   revapi24 = tpj("#rev_slider_24_1").show().revolution({
                       sliderType: "standard",
                       jsFileLocation: "revolution/js/",
                       sliderLayout: "fullscreen",
                       dottedOverlay: "none",
                       delay: 9000,
                       navigation: {
                           keyboardNavigation: "off",
                           keyboard_direction: "horizontal",
                           mouseScrollNavigation: "off",
                           mouseScrollReverse: "default",
                           onHoverStop: "off",
                           bullets: {
                               enable: true,
                               hide_onmobile: false,
                               style: "bullet-bar",
                               hide_onleave: false,
                               direction: "horizontal",
                               h_align: "center",
                               v_align: "bottom",
                               h_offset: 0,
                               v_offset: 50,
                               space: 5,
                               tmp: ''
                           }
                       },
                       responsiveLevels: [1240, 1024, 778, 480],
                       visibilityLevels: [1240, 1024, 778, 480],
                       gridwidth: [1240, 1024, 778, 480],
                       gridheight: [868, 768, 960, 720],
                       lazyType: "none",
                       shadow: 0,
                       spinner: "off",
                       stopLoop: "off",
                       stopAfterLoops: -1,
                       stopAtSlide: -1,
                       shuffle: "off",
                       autoHeight: "off",
                       fullScreenAutoWidth: "off",
                       fullScreenAlignForce: "off",
                       fullScreenOffsetContainer: "",
                       fullScreenOffset: "0px",
                       hideThumbsOnMobile: "off",
                       hideSliderAtLimit: 0,
                       hideCaptionAtLimit: 0,
                       hideAllCaptionAtLilmit: 0,
                       debugMode: false,
                       fallbacks: {
                           simplifyAll: "off",
                           nextSlideOnWindowFocus: "off",
                           disableFocusListener: false,
                       }
                   });
               }
               if (revapi24) revapi24.revSliderSlicey();
           }); /*ready*/
       </script>
   @endpush
