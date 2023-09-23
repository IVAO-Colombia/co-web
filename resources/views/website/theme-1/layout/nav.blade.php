  <!--Navigation-->
  <div id="mainMenu" class="">
      <div class="container">
          <nav>
              <ul>
                  <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                  <li class="dropdown"><a href="#">{{ __('Pilot') }}</a>
                      <ul class="dropdown-menu">
                          <li><a target="_blank"
                                  href="https://wiki.ivao.aero/en/home/training/main/pilot_rank">{{ __('Ratings') }}</a>
                          </li>
                          <li><a target="_blank" href="https://www.ivao.aero/softdev/software/altitude.asp">Altitude
                                  Software</a>
                          </li>
                      </ul>
                  </li>
                  <li class="dropdown"><a href="#">{{ __('ATC') }}</a>

                      <ul class="dropdown-menu">
                          <li> <a target="_blank"
                                  href="https://wiki.ivao.aero/en/home/training/main/rank_atc">{{ __('Ratings') }}</a>
                          </li>
                          <li> <a href="{{ route('front.fra') }}">{{ __('Which airport can I control?') }}</a> </li>
                          <li> <a href="{{ route('front.gca') }}">{{ __('Guest Controller Approval') }}</a> </li>
                          <li> <a
                                  href="{{ asset('theme-1/docs/public/Manual Sectorfile SKED-EC.pdf') }}">{{ __('Manual Aurora') }}</a>
                          </li>
                          <li> <a target="_blank" href="https://www.ivao.aero/softdev/software/aurora.asp">Aurora
                                  Software</a> </li>
                      </ul>


                  </li>
                  <li class="dropdown">
                      <a href="#">{{ __('Trainings') }}</a>
                      <ul class="dropdown-menu">

                          <li><a href="https://www.ivao.aero/training/training/statustraining.asp"
                                  target="_blank">{{ __('Request Training') }}</a></li>

                      </ul>
                  </li>
                  <li class="dropdown"><a href="#">{{ __('Community') }}</a>
                      <ul class="dropdown-menu">
                          <li> <a href="https://discord.co.ivao.aero"
                                  title="{{ __('Any problem? ask for support on our official discord') }}">{{ __('Discord') }}</a>
                          <li><a href="{{ route('front.virtualairlines') }}">{{ __('Virtual Airlines') }}</a></li>
                          <li><a href="{{ route('front.eventscalendar') }}">{{ __('Events Calendar') }}</a></li>


                      </ul>
                  </li>

                  </li>
                  <li class="dropdown"><a href="#">{{ __('utilities') }}</a>
                      <ul class="dropdown-menu">
                          <li><a href="http://co.forum.ivao.aero/" target="_blank">{{ __('Forum') }}</a>
                          <li><a href="https://tours.at.ivao.aero/index.php?div=CO"
                                  target="_blank">{{ __('Tours') }}</a>
                          <li><a href="https://eaip-colombia.atnaerocivil.gov.co/eaip/AIRAC%20AMDT%2064-22_2022_12_29/index.html"
                                  target="_blank">{{ __('eAIP') }}</a>
                          <li><a href="{{ route('front.docs') }}">{{ __('Documentation') }}</a>
                      </ul>
                  </li>
                  @if (Auth::check())
                      <li class="dropdown">
                          <a href="#">{{ Auth::user()->firstname }} - {{ Auth::user()->id }}</a>
                          <ul class="dropdown-menu">
                              <li><a href="{{ route('front.updateuser') }}" }}">{{ __('Profile') }}</a></li>
                              @if (isStaff(auth()->user()))
                                  <li><a href="{{ route('dashboard') }}">Staff</a></li>
                              @endif
                              <li>
                                  <a href="{{ route('ivao.logout') }}">{{ __('Logout') }}</a>
                              </li>
                          </ul>
                      </li>
                  @else
                      <li class="d-xl-none d-lg-none d-md-block d-sm-block">
                          <a href="{{ route('ivao.login') }}">{{ __('Login') }}</a>
                      </li>
                      <li class="d-xl-none d-lg-none d-md-block d-sm-block">
                          <a href="https://www.ivao.aero/members/person/register.htm" target="_blank">{{ __('Sign Up') }}</a>
                      </li>
                  @endif
              </ul>
          </nav>
      </div>
  </div>
  <!--end: Navigation-->
