<?php

namespace App\Http\Requests;

use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class DeleteContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO edit this
        $contactId = $this->route('contact'); // get contact id from route
        $contact = Contact::findOrFail($contactId); // get contact

        // Check if contact_user1_id is authorized by authorize
        if ($contact->contact_user1_id === Auth::id())
            return true;

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
