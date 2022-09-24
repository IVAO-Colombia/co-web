  <!--Navigation-->
  <div id="mainMenu" class="">
      <div class="container">
          <nav>
              <ul>
                  <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                  <li class="dropdown"><a href="#">{{ __('Pilot') }}</a>
                      <ul class="dropdown-menu">
                          <li><a target="_blank" href="https://wiki.ivao.aero/en/home/training/main/pilot_rank">Rangos</a>
                          </li>
                          <li><a target="_blank"
                                  href="http://www.aerocivil.gov.co/servicios-a-la-navegacion/servicio-de-informacion-aeronautica-ais">Cartas
                                  de Navegación</a>
                          </li>
                          <li><a target="_blank" href="https://www.ivao.aero/software/altitude">Altitude
                                  Software</a>
                          </li>
                      </ul>
                  </li>
                  <li class="dropdown"><a href="#">{{ __('ATC') }}</a>

                      <ul class="dropdown-menu">
                          <li> <a target="_blank"
                                  href="https://wiki.ivao.aero/en/home/training/main/rank_atc">Rangos</a>
                          </li>
                          <li> <a href="{{ url('/fra') }}">¿Cual aeropuerto puedo controlar?</a> </li>
                          <li> <a href="{{ url('/') }}">{{ __('Guest Controller Approval') }}</a> </li>
                          <li> <a target="_blank"
                                  href="http://www.aerocivil.gov.co/servicios-a-la-navegacion/servicio-de-informacion-aeronautica-ais">Cartas
                                  de Navegación</a> </li>
                          <li> <a target="_blank" href="https://www.ivao.aero/software/aurora">Aurora
                                  Software</a> </li>
                      </ul>


                  </li>
                  <li class="dropdown"><a href="#">{{ __('Traning') }}</a>
                      <ul class="dropdown-menu">

                          <li><a href="#">{{ __('Charts') }}</a></li>
                          <li><a href="#">{{ __('Calendar') }}</a></li>


                      </ul>
                  </li>
                  <li class="dropdown"><a href="#">{{ __('Community') }}</a>
                      <ul class="dropdown-menu">

                          <li><a href="#">{{ __('Charts') }}</a></li>
                          <li><a href="#">{{ __('Calendar') }}</a></li>


                      </ul>
                  </li>
                  <li><a href="http://co.forum.ivao.aero/" target="_blank">{{ __('Forum') }}</a>
                  </li>

                  @if (Auth::check())
                      <li class="dropdown">
                          <a href="#">{{ Auth::user()->firstname }} - {{ Auth::user()->id }}</a>
                          <ul class="dropdown-menu">
                              <li><a href="#" }}">Perfil</a></li>
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
                          <a href="https://ivao.aero/register" target="_blank">{{ __('Sign Up') }}</a>
                      </li>
                  @endif
              </ul>
          </nav>
      </div>
  </div>
  <!--end: Navigation-->
