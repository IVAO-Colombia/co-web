<div>
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200" wire:key='slider'>
        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/skmd.jpeg') }}');">
            <div class="bg-overlay" data-style="2"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->

                    <h1>{{ __('Profile') }}</h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->

    <section wire:key='container'>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>{{ __('Personal Information') }}</h2>

                    <form class="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" disabled value="{{ $user->firstname }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname">Last Name</label>
                                    <input type="text" class="form-control" value="{{ $user->lastname }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname">Rating ATC</label>
                                    <img src="https://ivao.aero/data/images/ratings/atc/{{ $user->ratingatc }}.gif"
                                        class="img-responsive" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname">Rating Pilot</label>
                                    <img src="https://ivao.aero/data/images/ratings/pilot/{{ $user->ratingpilot }}.gif"
                                        class="img-responsive" />
                                </div>
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="firstname">Email</label>
                                <input type="email" class="form-control" wire:model.defer="email" id="email"
                                    {{ !$editing ? 'disabled' : null }}>

                            </div>
                        </div>

                        <div>

                            @if ($editing)
                                <button class="btn btn-danger" wire:click="update">{{ __('Update') }}</button>
                            @else
                                <button class="btn btn-primary" wire:click="edit">{{ __('Edit') }}</button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
@push('scripts')
    <script>
        window.livewire.on('change-focus', function() {
            $("#email").focus();
        });
    </script>
@endpush
