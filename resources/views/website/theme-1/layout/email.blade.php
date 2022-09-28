@if (Auth::check())
    @if (strlen(Auth::user()->email) == 6)
        <div class="modal fade show" tabindex="-1" id="EmailModal" style="display: block; padding-right: 21px"
            aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="{{ route('front.usersUpdate') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <p>{{ __('Enter your email, in order to optimize and have a better experience with IVAO CO.') }}
                            </p>
                            <div class="mb-3">
                                <label for="Email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" name="email" class="form-control" id="Email" required />
                            </div>
                            <div class="text-right">
                                <button class="btn btn-secondary" type="submit">{{ __('Send') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
