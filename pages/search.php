<div class="m-4">
    <h1>Search by criteria</h1>

    <form id="searchForm" name="search" method="POST">
        <div class="form-row p-3">
            <div class="form-group col-md-4">
                <label for="inputCoverArea">Cover Area</label>
                <select name="coverArea" id="inputCoverArea" class="form-control">
                    <option value="" selected>Choose...</option>
                    <option>Area 1</option>
                    <option>Area 2</option>
                    <option>Area 3</option>
                    <option>Area 4</option>
                    <option>Area 5</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="inputTravelClass">Travel class</label>
                <select name="travelClass" id="inputTravelClass" class="form-control">
                    <option value="">Choose...</option>
                    <option>Single Trip</option>
                    <option>AMT</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Start Date</label>
                <div class='input-group date' id="datetimepicker1">
                    <input name="startDate" type='text' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<div class="m-4">
    <h1>Results:</h1>

    <div class="table-responsive">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Quote Number</th>
                <th scope="col">Quote Date</th>
                <th scope="col">Travel Class</th>
                <th scope="col">Cover Area</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Traveller Id</th>
                <th scope="col">Conditions</th>
            </tr>
            </thead>
            <tbody id="results">

            </tbody>
        </table>
    </div>

    <ul class="pagination mx-auto text-justify w-25">
    </ul>

</div>

<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datepicker({
            format: 'yyyy-mm-dd',
        });
    });

    $(document).ready(function () {
        var page = 1;
        var pageLimit = 10;
        var totalRecords = 0;
        var totalPages = 0;

        // first time fetching data
        fetchData();

        // Handling page buttons
        $('.pagination').on('click', '.first', function() {
            if (page > 1) {
                page = 1;
                fetchData();
            }
        });
        $('.pagination').on('click', '.previous', function() {
            if (page > 1) {
                page--;
                fetchData();
            }
        });

        $('.pagination').on('click', '.page-num', function() {
            page = this.id;
            fetchData();

        });

        $('.pagination').on('click', '.next', function() {
            if ((page * pageLimit) < totalRecords) {
                page++;
                fetchData();
            }
        });

        $('.pagination').on('click', '.last', function() {
            if ((page * pageLimit) < totalRecords) {
                page = totalPages;
                console.log(totalPages);
                fetchData();
            }
        });

        // on form submit fetch data without refreshing page
        $("#searchForm").submit(function(e){
            e.preventDefault();
            fetchData();
        });

        // sends form data with ajax and creating the template on success
        function fetchData() {
            var formData = new FormData($('#searchForm')[0]);
            formData.append('page', page);

            $.ajax({
                type: 'POST',
                url: './include/fetch_data.php',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function (result) {
                    $('#results').empty();
                    result = JSON.parse(result);

                    totalPages = result.totalPages;
                    totalRecords = result.records.length;

                    if('records' in result){

                        $('.pagination').empty();
                        $('.pagination').append(result.links);
                        result.records.map(function (record) {

                            // parsing xml for conditions
                            var parser = new DOMParser();
                            var xmlRecord = parser.parseFromString(record[6],"text/xml");
                            var conditions = xmlRecord.getElementsByTagName("conditions")[0].getElementsByTagName("Condition");

                            // creating template for each row
                            var template = '<tr>'+
                                   '<td>' + record[0] + '</td>'+
                                   '<td>' + record[1] + '</td>'+
                                   '<td>' + record[2] + '</td>'+
                                   '<td>' + record[3] + '</td>'+
                                   '<td>' + record[4] + '</td>'+
                                   '<td>' + record[5] + '</td>'+
                                   '<td>' + record[7] + '</td>'+
                                   '<td>';

                            for (var i = 0; i < conditions.length; i++) {
                                template += conditions[i].getElementsByTagName("name")[0].firstChild.nodeValue + "<br>";
                            }
                            template += '</td>'+
                                '</tr>';

                            $('#results').append(template);
                        });
                    }
                }
            });
        }
    })
</script>