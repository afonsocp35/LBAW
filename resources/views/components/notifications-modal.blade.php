<div id="notifications-modal" class="notifications-modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeNotifications()">&times;</span>
        <h2>Notifications</h2>
        <ul class="notifications-list">
            @foreach($notifications as $notification)
            <li>
                <strong>{{ $notification->id ?? 'No Name' }}</strong><br>
            </li>
        @endforeach
        </ul>
    </div>
</div>



<script>
    // Function to toggle the notification modal
    function toggleNotifications() {
        var modal = document.getElementById("notifications-modal");
        modal.style.display = modal.style.display === "block" ? "none" : "block";
    }

    // Function to close the notification modal
    function closeNotifications() {
        document.getElementById("notifications-modal").style.display = "none";
    }
</script>