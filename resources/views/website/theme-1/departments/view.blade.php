<div class="" wire:id="{{ $this->id }}">
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns"
            style="background-image:url('{{ asset('storage/departments/'.$department->banner) }}');">
            <div class="bg-overlay" data-style="10"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <h1>{{ $department->title }}</h1>
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->

    <section>


        @if (Auth::check() && isStaff(auth()->user()) && !$edit && $canEdit)
        <div class="position-absolute m-5">
            <button class="btn btn-info" wire:click="editDepartment" title="{{__('edit')}}"><span
                    class="material-symbols-outlined">
                    edit
                </span>
            </button>
        </div>
        @endif

        <div class="container">

            <div class="row justify-center flex">
                <div class="content col-md-12 my-2 text-left ">

                    @if (session()->has('message'))
                    <div class="alert alert-success mt-3">
                        {{ session('message') }}
                    </div>
                    @elseif (session()->has('error'))
                    <div class="alert alert-danger mt-3">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if(!$edit)
                    <div class="tag-box tag-box-v1 margin-bottom-40 heading-text heading-light font-light">
                        <p>{!! $department->description !!}
                        </p>
                    </div>
                    @else
                    <div class="tag-box tag-box-v1 margin-bottom-40 heading-text heading-light font-light">

                        {{-- Titulo --}}
                        <div class="mb-3" wire:ignore>
                            <label for="title" class="form-label">Titulo</label>
                            <input type="text" class="form-control" id="title" wire:model.live="title"
                                aria-describedby="titulo">
                        </div>

                        {{-- Descripcion --}}
                        <div class="mb-3" wire:ignore>
                            <label for="trix-content" class="form-label">Department information</label>

                            <input id="trix-content" type="hidden" name="content" value="{{$department->description}}">
                            <trix-editor input="trix-content"></trix-editor>
                        </div>

                        {{-- BANNER --}}
                        <div class="mb-3" wire:ignore>
                            <label for="department_image" class="form-label">Banner department</label>
                            <input type="file" name="department_image" wire:model="bannerInput" type="image"
                                id="department_image">
                            @error('file')
                            <span class="text-warning">{{ $message }}</span>
                            @enderror
                            <div wire:loading wire:target="bannerInput" class=""><img
                                    src="{{ asset('img/Spinner-1s-200px.svg') }}" alt="carga"></div>
                            @if ($bannerInput)
                            <div class="">
                                <img src="{{ $bannerInput->temporaryUrl() }}">
                            </div>
                            @endif
                        </div>

                        <div class="mb-3" wire:ignore>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="flexSwitchCheckChecked" wire:model="hasEvents">
                                <label class="form-check-label" for="flexSwitchCheckChecked">¿Tiene eventos?</label>
                            </div>
                        </div>

                        <div class="mb-3" wire:ignore>
                            <button class="btn" id="saveButton" type="button">Guardar</button>
                            <button class="btn btn-danger" type="button">Cancelar</button>
                        </div>
                    </div>
                    @endif
                </div>
                @if ($this->department->events)
                <div class="">
                    <h2>Proximos eventos</h2>
                    <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">

                        <!-- Static Post Item 1 -->
                        <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="0">
                            <div class="post-item-wrap">
                                <div class="post-image">
                                    <a href="#link1">
                                        <img alt="Manual Sectorfile" src="{{ asset('img/aurora_manual.png') }}" />
                                    </a>
                                </div>
                                <div class="post-item-description">
                                    <span class="post-meta-date"><i class="fa fa-calendar-o"></i> 28 Nov 2024</span>
                                    <h2>
                                        <a href="{{ asset('theme-1/docs/public/Manual Sectorfile SKED-EC.pdf') }}"
                                            target="_blank" rel="noopener noreferrer" class="text-capitalize">Manual
                                            Sectorfile</a>
                                    </h2>
                                    <p style="white-space: wrap; overflow: hidden; text-overflow: ellipsis;">
                                        Manual de funciones específicas de la división implementadas en Aurora.
                                    </p>
                                    <a href="{{ asset('theme-1/docs/public/Manual Sectorfile SKED-EC.pdf') }}"
                                        target="_blank" rel="noopener noreferrer" class='item-link'>Abrir <i
                                            class="icon-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Static Post Item 2 -->
                        <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="400">
                            <div class="post-item-wrap">
                                <div class="post-image">
                                    <a href="#link2">
                                        <img alt="Event Title 2" src="{{ asset('img/wip.gif') }}" />
                                    </a>
                                </div>
                                <div class="post-item-description">
                                    <span class="post-meta-date"><i class="fa fa-calendar-o"></i> 29 Nov 2024</span>
                                    <h2>
                                        <a href="#link2" class="text-capitalize">CPDLC y DLC</a>
                                    </h2>
                                    <p style="white-space: wrap; overflow: hidden; text-overflow: ellipsis;">
                                        ¿Cómo usar CPDLC en Aurora? ¿Qué es DLC y cómo se usa?
                                    </p>
                                    <a href="#link2" class='item-link'>Abrir <i class="icon-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Static Post Item 3 -->
                        <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="800">
                            <div class="post-item-wrap">
                                <div class="post-image">
                                    <a href="#link3">
                                        <img alt="Event Title 3" src="{{ asset('img/wip.gif') }}" />
                                    </a>
                                </div>
                                <div class="post-item-description">
                                    <span class="post-meta-date"><i class="fa fa-calendar-o"></i> 30 Nov 2024</span>
                                    <h2>
                                        <a href="#link3" class="text-capitalize">Usando Aurora</a>
                                    </h2>
                                    <p style="white-space: wrap; overflow: hidden; text-overflow: ellipsis;">
                                        Aprende cómo usar Aurora en eventos y en control diario.
                                    </p>
                                    <a href="#link3" class='item-link'>Abrir <i class="icon-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        Livewire.on('editModeEnabled', function () {
            console.log("Modo editar: activo");

            const trixEditor = document.querySelector("trix-editor");
            trixEditor.editor.loadHTML(@this.get('description'));


            const saveButton = document.getElementById("saveButton");


            saveButton.addEventListener("click", () => {
                const hiddenInput = document.querySelector('#trix-content');
                const content = hiddenInput.value;

                @this.set('description', content);
                @this.call('store')
            });

        });

    </script>
    @endpush



    {{--

    // Captura el contenido al hacer blur
    // document.addEventListener('trix-blur', function () {
    // if (trixInput) {
    // @this.set('description', trixInput.value);
    // }
    // });

    // Captura al escribir (opcional)
    // document.addEventListener('trix-change', function () {
    // @this.set('description', trixInput.value);
    // });
    --}}








</div>
