<h1>Create Message</h1>

<form method="post" action="/messages">
    @csrf
    <input type="text" name="receiver_id">
    <input type="text" name="message">

    <input type="submit" value="Save">
</form>
