<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{url('css/font-awesome.css')}}">
    <link rel="stylesheet" href="{{url('css/style.css')}}">
    <link rel="stylesheet" href="{{url('bootstrap/css/bootstrap.min.css')}}">
    <script src="{{url('js/jquery.min.js')}}"></script>
    <script>
        //my-js
        var option_numbers;
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
        function add_more_specimen()
        {
            next_specimen_num += 1;

            if(next_specimen_num<=500)
            {
                var new_specimen = "<div class='row'>"+
                        "<div class='col-md-4'><h6>SPECIMEN "+next_specimen_num+"</h6></div>"+
                        "<div class='col-md-4'><input type='text' id='specimen_"+next_specimen_num+"_supply' required placeholder='e.g 1,2,5'></div>"+
                        "<div class='col-md-2'><select class='specimen_combination_num' style='width: 30%' id='specimen_"+next_specimen_num+"_combination_num'></select></div>"+
                        "<div class='col-md-2'><input type='submit' onclick='specimen_start("+next_specimen_num+");' class='btn btn-primary btn-sm' value='Generate'></div>"+
                        "</div><hr>";
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
            if(($("#original_combination_num").val())<=($("#original_end_num").val())){
                $('.loader').show();
                combination_html = '<tr><th>Original data combinations</th></tr>';
                var start = $("#original_start_num").val();
                var end = $("#original_end_num").val();
                var combination_size = parseInt($("#original_combination_num").val());
                var rang = range(start, end);
                combination_array = arrayCreate(rang, combination_size).map(a => a.join(''));
                for(var i = 0; i<combination_array.length; i++)
                {
                    combination_html+="<tr><td>"+combination_array[i] +"</td></tr>";
                }
                $("#combinations_items_table").html(combination_html);
                $('.loader').hide();
            }
            else
                alert('Size of combination must not be greater than the size of original number');
        }
        var specimen_all_array =[];
        function specimen_start(num)
        {
            if(($("#specimen_"+num+"_supply").val())!=''){
                if((JSON.parse('['+$("#specimen_"+num+"_supply").val()+']').length) >= ($("#specimen_"+num+"_combination_num").val())) {
                    var combination_html = '<tr><th>Specimen '+num+ ' data combinations</th></tr>';
                    var supply = $("#specimen_"+num+"_supply").val();
                    //the below converts supply seperated by comma to array
                    var supply_array = JSON.parse('['+supply+']');
                    var combination_size = parseInt($("#specimen_"+num+"_combination_num").val());
                    var combination_array = arrayCreate(supply_array, combination_size).map(a => a.join(''));
//                    specimen_all_array = combination_array;
                    for(var i = 0; i<combination_array.length; i++)
                    {
                        combination_html+="<tr><td>"+combination_array[i] +"</td></tr>";
                        specimen_all_array[specimen_all_array.length] = combination_array[i];
                    }
                    if(num>3){
                        $("#specimen_random_combinations_items_table").html(combination_html);
                    }
                    else
                        $("#specimen_"+num+"_combinations_items_table").html(combination_html);
                }
                else
                    alert("Size of combination must not be greater than supply length");
            }
            else
                alert('Empty supply is not allowed');
        }

        function remaining() {
            var combination_html = '<tr><th>Remaining data </th></tr>';
//            makes "specimen_all_array" uniqu
            var set = new Set(specimen_all_array);
            specimen_all_array = Array.from(set);

//            perform difference of a set oroginal data - all_specime data
            var a = new Set(combination_array);
            var b = new Set(specimen_all_array);
            var difference = new Set([...a].filter(x => !b.has(x)));
//          turns set back to array
            difference = Array.from(difference);

//            print it out
            for(var i = 0; i<difference.length; i++)
            {
                combination_html+="<tr><td>"+difference[i] +"</td></tr>";
            }
            $("#remaining_item_table").html(combination_html);

        }
    </script>
    <meta charset="UTF-8">
    <title>Cobination</title>
</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Combination.</a>
        </div>
    </div><!-- /.container-fluid -->
</nav>


<div class="container">
    <div class="loader_container"><div class="loader"></div></div>
    <div id="specimen_world">
        <div class="row" style="background-color:#eceaea">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <h6>Start &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; End</h6>
            </div>
            <div class="col-md-2">
                <h6>Combination</h6>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4"><h6>ORIGINAL DATA</h6></div>
            <div class="col-md-4">
                <select class="class_start_num" style="width: 30%" id="original_start_num">
                </select>
                <select class="class_start_num" style="width: 30%" id="original_end_num">
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
        <hr>
        <div class="row" style="background-color:#eceaea;">
            <div class="col-md-4"><h6>SPECIMENS</h6></div>
            <div class="col-md-4">
                <h6>Supply</h6>
            </div>
            <div class="col-md-2">
                <h6>Combination</h6>
            </div>
            <div class="col-md-2">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4"><h6>SPECIMEN 1</h6></div>
            <div class="col-md-4">
                <input type="text" id="specimen_1_supply" required placeholder="e.g 1,2,5">
            </div>
            <div class="col-md-2">
                <select class="specimen_combination_num" style="width: 30%" id="specimen_1_combination_num">
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" onclick="specimen_start(1);" class="btn btn-primary btn-sm" value="Generate">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4"><h6>SPECIMEN 2</h6></div>
            <div class="col-md-4">
                <input type="text" id="specimen_2_supply" required placeholder="e.g 1,2,5">
            </div>
            <div class="col-md-2">
                <select class="specimen_combination_num" style="width: 30%" id="specimen_2_combination_num">
                </select>
            </div>
            <div class="col-md-2">
                <input type="submit" onclick="specimen_start(2);" class="btn btn-primary btn-sm" value="Generate">
            </div>
        </div>
        <hr>
    </div>
    <hr>
    <input type="submit" onclick="add_more_specimen();" class="btn btn-success btn-sm" value="+ specimen">
    <input type="submit" onclick="remaining();" class="btn btn-primary pull-right btn-sm" value="Original data - all specimen">
    {{--<button type="button" onclick="add_more_specime" class="btn btn-primary">Add<i class="fa fa-plus"></i> </button>--}}
    <h4>Results</h4>
    <hr>
    <div class="row">
        <div class="col-md-2"><table id="combinations_items_table" class="table table-striped"></table></div>
        <div class="col-md-2"><table id="specimen_1_combinations_items_table" class="table table-striped"></table></div>
        <div class="col-md-2"><table id="specimen_2_combinations_items_table" class="table table-striped"></table></div>
        <div class="col-md-2"><table id="specimen_3_combinations_items_table" class="table table-striped"></table></div>
        <div class="col-md-2"><table id="specimen_random_combinations_items_table" class="table table-striped"></table></div>
        <div class="col-md-2"><table id="remaining_item_table" class="table table-striped"></table></div>
    </div>

</div>


</body>
</html>