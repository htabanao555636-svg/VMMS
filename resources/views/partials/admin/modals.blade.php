<!-- Certificate Modal -->
<div id="certificateModal" class="modal">
    <div class="modal-content">
        <h2>Certificate</h2>
        <img id="certImage" class="cert-image">
        <button onclick="closeCertificateModal()">Close</button>
    </div>
</div>

<!-- Add Mechanic Modal -->
<div id="addMechanicModal" class="modal">
    <div class="modal-content">
        <h2>Add Mechanic</h2>
        <form id="addMechanicForm">
            <input type="text" id="mechName" placeholder="Name" required>
            <button onclick="submitAddMechanic()">Save</button>
        </form>
    </div>
</div>
