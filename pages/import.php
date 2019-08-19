<div class="m-4">
    <h1>Create new Quote</h1>

    <span id="message"></span>
    <form id="importForm" method="POST" class="m-5 w-50">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Product</label>
            <div class="col-sm-10">
                <select name="product" class="form-control">
                    <option value="">Choose...</option>
                    <option>Island</option>
                    <option>Lakeland</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Affiliate</label>
            <div class="col-sm-10">
                <select name="affiliate" class="form-control">
                    <option value="">Choose...</option>
                    <option>Baikal</option>
                    <option>Malawi</option>
                    <option>Seychelles</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Travel type</label>
            <div class="col-sm-10">
                <select name="travelType" class="form-control">
                    <option value="">Choose...</option>
                    <option>Couple</option>
                    <option>Group</option>
                    <option>Family1</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="inputTravelClass" class="col-sm-2 col-form-label">Travel class</label>
            <div class="col-sm-10">
                <select name="travelClass" id="inputTravelClass" class="form-control">
                    <option value="">Choose...</option>
                    <option>Single Trip</option>
                    <option>AMT</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputCoverArea" class="col-sm-2 col-form-label">Cover Area</label>
            <div class="col-sm-10">
                <select name="coverArea" id="inputCoverArea" class="form-control">
                    <option value="" selected>Choose...</option>
                    <option>Area 1</option>
                    <option>Area 2</option>
                    <option>Area 3</option>
                    <option>Area 4</option>
                    <option>Area 5</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Start Date</label>
            <div class="col-sm-10">
                <div class='input-group date' id="datetimepicker1">
                    <input name="startDate" type='text' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">End Date</label>
            <div class="col-sm-10">
                <div class='input-group date' id="datetimepicker2">
                    <input name="endDate" type='text' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Select XML File</label>
            <div class="col-sm-10">
                <div class='input-group date'>
                    <input name="file" type='file' class="form-control border-0" />
                </div>
            </div>
        </div>


        <div class="form-group row">
            <div class="col-sm-10">
                <button type="submit" id="submitBtn" class="btn btn-primary">Create</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        // datepicker
        $('#datetimepicker1 , #datetimepicker2').datepicker({
            format: 'yyyy-mm-dd',
        });

        // on form submit fetch data without refreshing page
        $("#importForm").submit(function (e) {
            e.preventDefault();
            sendData();
        });
        
        function sendData() {
            $.ajax({
                type: 'POST',
                url: './include/import_data.php',
                data: new FormData($('#importForm')[0]),
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $('#submitBtn').text('Creating Quote...');
                },
                success: function (data) {

                    $('#message').html(data);
                    $('#importForm')[0].reset();
                    $('#submitBtn').text('Create');
                }
            });

            setInterval(function () {
                $('#message').html('');
            }, 3000);
        }
    });
</script>