<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{url('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{url('css/style.css')}}">
    <link rel="stylesheet" href="{{url('bootstrap/css/bootstrap.min.css')}}">
    <script src="{{url('js/jquery.min.js')}}"></script>
    <script>
        //my-js
        var option_numbers='';
        $(document).ready(function(){
            $('.loader').hide();
            for(var i = 1; i<=90; i++)
            {
                option_numbers+= "<option id='' value='" +i+ "'>"+i+"</option>";
            }
            $(".class_start_num").html(option_numbers);
            $(".specimen_combination_num").html(option_numbers);
        });
        var next_specimen_num=2;

        //        last speciment number
        var last_specimen_number = 2;

        function add_more_specimen()
        {
            next_specimen_num += 1;

            if(next_specimen_num<=500)
            {
                var new_specimen =
                        "<div class='row'><div class='col-md-2'><h6> Specimen "+next_specimen_num+" </h6></div><div class='col-md-2'><h6> A</h6></div><div class='col-md-4'>" +
                        // input a
                        "<input type='text' id='specimen_a_"+next_specimen_num+"_supply' required placeholder='e.g 1,2,5'>" +
                        "</div><div class='col-md-2'>"+
                        //combination a
                        "<select class='specimen_combination_num' style='width: 30%' id='specimen_a_"+next_specimen_num+"_combination_num'></select>" +
                        "</div><div class='col-md-2'></div></div>"+
                        "<div class='row'><div class='col-md-2'></div><div class='col-md-2'><h6> B</h6></div><div class='col-md-4'>" +
                        // input b
                        "<input type='text' id='specimen_b_"+next_specimen_num+"_supply' required placeholder='e.g 1,2,5'>" +
                        "</div><div class='col-md-2'>"+
                        //combination b
                        "<select class='specimen_combination_num' style='width: 30%' id='specimen_b_"+next_specimen_num+"_combination_num'></select>" +
                        "</div><div class='col-md-2'></div></div><hr>";
                $("#specimen_world").append(new_specimen);
                $(".specimen_combination_num").html(option_numbers);
            }
            else
                alert("Maximum number of specimen");
        }
        function arrayCreate(array, size) {
            var result = [];
            array.forEach(function iter(parts) {
                return function (v) {
                    var temp = parts.concat(v);
                    if (parts.includes(v)) {
                        return;
                    }
                    if (temp.length === size) {
                        result.push(temp);
                        return;
                    }
                    array.forEach(iter(temp));
                }
            }([]));
            return result;
        }
        function range(start, end)
        {
            var the_range = [];
            for(var i = start; i<=end; i++)
            {
                the_range.push(parseInt(i));
            }
            return the_range;
        }

        var combination_array;
        function start()
        {
            var start_num = parseInt($("#original_start_num").val());
            var end_num = parseInt($("#original_end_num").val());
            var combination = parseInt($("#original_combination_num").val());

            if(combination <= (end_num-start_num)+1){
                $('.loader').show();

                var start = $("#original_start_num").val();
                var end = $("#original_end_num").val();
                var combination_size = parseInt($("#original_combination_num").val());
                var rang = range(start, end);
                combination_array = arrayCreate(rang, combination_size).map(a => a.join(''));

                combination_html = '<tr><th>Original data result '+combination_array.length+'</th></tr>';
                for(var i = 0; i<combination_array.length; i++)
                {
                    combination_html+="<tr><td>"+combination_array[i] +"</td></tr>";
                }
                $("#combinations_items_table").html(combination_html);
                $('.loader').hide();
            }
            else
            {
                alert('Size of combination must not be greater than the range of original number');
            }
        }
        var specimen_all_array =[];
        function specimen_start(num)
        {
            var supply_a = $("#specimen_a_"+num+"_supply").val();
            var supply_b = $("#specimen_b_"+num+"_supply").val();

            //the below converts supply seperated by comma to array
            var supply_array_a = JSON.parse('['+supply_a+']');
            var supply_array_b = JSON.parse('['+supply_b+']');

            var combination_size_a = parseInt($("#specimen_a_"+num+"_combination_num").val());
            var combination_size_b = parseInt($("#specimen_b_"+num+"_combination_num").val());

            var combination_array_a = arrayCreate(supply_array_a, combination_size_a).map(a => a.join(''));
            var combination_array_b = arrayCreate(supply_array_b, combination_size_b).map(a => a.join(''));

            var array_a_and_b = combination_array_a.concat(combination_array_b);
            //  makes "combination_array_a and combination_array_b" uniqu
            var set = new Set(array_a_and_b);
            array_a_and_b = Array.from(set);

            //  perform difference of a set oroginal data - all_specime data
            var a = new Set(combination_array);
            var b = new Set(array_a_and_b);
            var difference = new Set([...a].filter(x => !b.has(x)));
            //   turns set back to array
            difference = Array.from(difference);
            specimen_all_array[num-1] = difference;
            // tell us the number of result for this specimen
            var combination_html = '<div class="col-md-2"><table class="table table-striped"><tr><th>Specimen '+num+ ' result ('+difference.length +') </th></tr>';

            //   print it out
            for(var i = 0; i<difference.length; i++)
            {
                combination_html+="<tr><td>"+difference[i] +"</td></tr>";
            }

            combination_html+="</table></div>";
            $("#result_data_div").append(combination_html);
        }

        function remaining() {

            for(var num =1; num<=next_specimen_num; num++)
            {
                if(($("#specimen_a_"+num+"_supply").val())=='' || ($("#specimen_b_"+num+"_supply").val())=='') {
                    error +=1;
                    alert("Empty supply in specimen "+num);
                    break;
                }
                else
                {
                    if ((JSON.parse('[' + $("#specimen_a_" + num + "_supply").val() + ']').length) < ($("#specimen_a_" + num + "_combination_num").val()) || (JSON.parse('[' + $("#specimen_b_" + num + "_supply").val() + ']').length) < ($("#specimen_b_" + num + "_combination_num").val())) {
                        error +=1;
                        alert("Size of combination must not be greater than supply length in SPECIMEN " + num);
                        break;
                    }
                }
            }
            specimen_all_array = [];
            var error = 0;
            if(!combination_array)
            {
                start();
            }
            if(error<1){
                do_remaining();
            }
        }

        function do_remaining() {
            $("#result_data_div").empty();
            for (var i = 1; i <= next_specimen_num; i++) {
                specimen_start(i);
            }
            get_all_common_elements();
        }


        function get_all_common_elements() {
            function getCommonElements(specimen_all_array){//Assumes that we are dealing with an array of specimen_all_array of integers
                var currentValues = {};
                var commonValues = {};
                for (var i = specimen_all_array[0].length-1; i >=0; i--){//Iterating backwards for efficiency
                    currentValues[specimen_all_array[0][i]] = 1; //Doesn't really matter what we set it to
                }
                for (var i = specimen_all_array.length-1; i>0; i--){
                    var currentArray = specimen_all_array[i];
                    for (var j = currentArray.length-1; j >=0; j--){
                        if (currentArray[j] in currentValues){
                            commonValues[currentArray[j]] = 1; //Once again, the `1` doesn't matter
                        }
                    }
                    currentValues = commonValues;
                    commonValues = {};
                }
                return Object.keys(currentValues).map(function(value){
                    return parseInt(value);
                });
            }
            var all_common_element = getCommonElements(specimen_all_array);
            console.log(getCommonElements(specimen_all_array)); //Prints [9,44]
            var combination_html = '<div class="col-md-2"><table class="table table-striped" style="background-color: #5cb85cbd;"><tr><th>Common to all specimens('+all_common_element.length +') </th></tr>';

//            print it out
            for(var i = 0; i<all_common_element.length; i++)
            {
                combination_html+="<tr><td>"+all_common_element[i] +"</td></tr>";
            }
            combination_html+="</table></div>";
            $("#result_data_div").append(combination_html);

        }

    </script>
    <meta charset="UTF-8">
    <title>Cobination</title>
</head>
<body>

<nav class="navbar navbar-inverse" style="margin-bottom: 0px; ">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Combination.</a>
        </div>
    </div><!-- /.container-fluid -->
</nav>


<div class="container">
    {{--<div class="loader_container"><div class="loader"></div></div>--}}
    <div id="specimen_world">
        <div class="row" style="background-color:#eceaea">
            <div class="col-md-4"><h5>Original Data</h5></div>
            <div class="col-md-4">
                <h5>Start &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; End</h5>
            </div>
            <div class="col-md-2">
                <h5>Combination</h5>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <select class="class_start_num" style="width: 30%" id="original_start_num">
                </select>
                <select class="class_start_num" style="width: 30%" id="original_end_num" selected="4">
                </select>
            </div>
            <div class="col-md-2">
                <select class="class_start_num" style="width: 30%" id="original_combination_num">
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" onclick="start();" class="btn btn-primary btn-sm" value="Generate">
            </div>
        </div>
        <div class="row" style="background-color:#eceaea;">
            <div class="col-md-4"><h5>Specimens</h5></div>
            <div class="col-md-4">
                <h5>Supply</h5>
            </div>
            <div class="col-md-2">
                <h5>Combination</h5>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2"><h6>SPECIMEN 1</h6></div>
            <div class="col-md-2"><h6>A</h6></div>
            <div class="col-md-4">
                <input type='text' id='specimen_a_1_supply' required placeholder='e.g 1,2,5' value="1,2">
            </div>
            <div class="col-md-2">
                <select class="specimen_combination_num" style="width: 30%" id="specimen_a_1_combination_num">
                </select>
            </div>
            <div class="col-md-2">

            </div>
        </div>

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-2"><h6>B</h6></div>
            <div class="col-md-4">
                <input type="text" id="specimen_b_1_supply" required placeholder="e.g 1,2,5" value="1,3">
            </div>
            <div class="col-md-2">
                <select class="specimen_combination_num" style="width: 30%" id="specimen_b_1_combination_num">
                </select>
            </div>
            <div class="col-md-2">

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-2">Specimen 2</div>
            <div class="col-md-2"><h6>A</h6></div>
            <div class="col-md-4">
                <input type='text' id='specimen_a_2_supply' required placeholder='e.g 1,2,5' value="1,2">
            </div>
            <div class="col-md-2">
                <select class="specimen_combination_num" style="width: 30%" id="specimen_a_2_combination_num">
                </select>
            </div>
            <div class="col-md-2">

            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-2"><h6>B</h6></div>
            <div class="col-md-4">
                <input type="text" id="specimen_b_2_supply" required placeholder="e.g 1,2,5" value="1,3">
            </div>
            <div class="col-md-2">
                <select class="specimen_combination_num" style="width: 30%" id="specimen_b_2_combination_num">
                </select>
            </div>
            <div class="col-md-2">

            </div>
        </div>
        <hr>
    </div>
    <br>
    <input type="submit" onclick="add_more_specimen();" class="btn btn-success btn-sm" value="+ specimen">
    <input type="submit" onclick="remaining();" class="btn btn-primary pull-right btn-sm" value="Generate all.">
    {{--<button type="button" onclick="add_more_specime" class="btn btn-primary">Add<i class="fa fa-plus"></i> </button>--}}
    <h4>Results</h4>
    <hr>
    <div class="row" id="">
        <div class="col-md-2"><table id="combinations_items_table"  style="background-color: #5cb85cbd" class="table table-striped"></table></div>
        <div id="result_data_div"></div>
    </div>
    {{--<div class="row">--}}
    {{--<div class="col-md-2"><table id="combinations_items_table" class="table table-striped"></table></div>--}}
    {{--<div class="col-md-2"><table id="specimen_1_combinations_items_table" class="table table-striped"></table></div>--}}
    {{--<div class="col-md-2"><table id="specimen_2_combinations_items_table" class="table table-striped"></table></div>--}}
    {{--<div class="col-md-2"><table id="specimen_3_combinations_items_table" class="table table-striped"></table></div>--}}
    {{--<div class="col-md-2"><table id="specimen_random_combinations_items_table" class="table table-striped"></table></div>--}}
    {{--<div class="col-md-2"><table id="remaining_item_table" class="table table-striped"></table></div>--}}
    {{--</div>--}}

</div>


</body>
</html>