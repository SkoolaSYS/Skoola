<form method="POST" action="/attendance-parent-request">
    @csrf
    <input type="email" name="email" value="parent@example.com">
    <button type="submit">Send Verification Link</button>
</form>
