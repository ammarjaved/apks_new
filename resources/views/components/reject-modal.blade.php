<div class="modal fade" id="rejectReasonModal">
    <div class="modal-dialog">
        <div class="modal-content ">

            <!-- Modal Header -->
            <div class="modal-header">
                <h6 class="modal-title">Reject</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="reject-foam" method="GET">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="id" id="reject-id">
                    <input type="hidden" name="status" id="qa_status" value="Reject">
                    <label for="reject">Reject Remarks : </label>
                    <textarea name="reject_remakrs" id="reject_remakrs" cols="20" rows="5" class="form-control" placeholder="enter resaon" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">update</button>
                </div>
            </form>

        </div>
    </div>
</div>



<div class="modal fade" id="rejectReasonModalShow">
    <div class="modal-dialog">
        <div class="modal-content ">

            <!-- Modal Header -->
            <div class="modal-header">
                <h6 class="modal-title">Reject Reason</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            

                <div class="modal-body">
                    <input type="hidden" name="id" id="reject-id">
                    <input type="hidden" name="status" id="qa_status" value="Reject">
                    <label for="reject">Reject Remarks : </label>
                    <textarea name="reject_remakrs" id="reject_remakrs_show" disabled readonly cols="20" rows="5" class="form-control" placeholder="enter resaon" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    
                </div>
          

        </div>
    </div>
</div>