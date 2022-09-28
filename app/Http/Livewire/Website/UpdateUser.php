<?php

namespace App\Http\Livewire\Website;

use Livewire\Component;
use App\Models\User;

class UpdateUser extends Component
{
    public $email,
        $editing = false;
    public User $user;

    protected $rules = ["email" => "required|email"];

    public function mount()
    {
        $this->user = User::find(auth()->user()->id);
        $this->email = $this->user->email;
    }

    public function render()
    {
        return view("livewire.website.update-user")->extends(
            "website.theme-1.layout.theme-1"
        );
    }

    public function edit()
    {
        $this->editing = true;
        $this->emit("change-focus");
    }

    public function update()
    {
        $this->user->email = $this->email;
        $this->user->save();
        $this->editing = false;

        $this->emit("toast", [
            "message" => "Excellent we have updated your email!",
            "title" => "Success!",
        ]);

        // session()->flash(
        //     "flash.banner",
        //     "Excellent we have updated your email!"
        // );
        // session()->flash("flash.bannerStyle", "success");
    }
}
