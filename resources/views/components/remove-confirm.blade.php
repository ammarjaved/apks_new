<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content ">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Remove Recored</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="" id="remove-foam" method="POST">
                @method('DELETE')
                @csrf

                <div class="modal-body">
                    Are You Sure ?
                    <input type="hidden" name="id" id="modal-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-danger">Remove</button>
                </div>
            </form>

        </div>
    </div>
</div>