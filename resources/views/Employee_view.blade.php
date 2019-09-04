<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.4.3/css/mdb.min.css">
    <title>Document</title>

    <style>
        .btn {

            border: none;
            color: black;
            padding: 5px 5px;
            text-align: center;
            font-size: 20px;
            margin: 4px 5px 0px 50px;
            transition: 0.3s;
        }

        .btn:hover {
            width: 100;
            background-color: #3e8e41;
            color: white;
        }

        ul.my {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #4c3c3c;
            width: 50%;
        }

        li.my {
            float: left;
        }

        li a {
            height: 50px;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 11px;
            text-decoration: none;
        }

        /* Change the link color to #111 (black) on hover */
        li a:hover {
            background-color: #111;
        }

        .colum-layout1 {
            max-width: 1300px;
            margin: 40px auto 0 auto;
            padding: auto;
            display: flex;
            align-items: flex-start;

        }

        .main-column1 {
            flex: 0.5;
            margin-right: 40px;

        }

        .second-column1 {
            flex: 1;

        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: white;
        }

        td,
        th {
            padding: 6px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .my-custom-scrollbar {
            position: relative;
            height: 580px;
            overflow: auto;

        }

        .table-wrapper-scroll-y {
            display: block;
        }

        .avatar {
            vertical-align: middle;
            width: 200px;
            height: 200px;
            border-radius: 50%;
        }

        .center {
            text-align: center;

        }
    </style>
</head>

<body>

<!-- Grid row pic zoom hover code -->


<!-- Grid column ---hover code for pic ---
            <div class="col-md-6 mb-4">

                <div class="view overlay hm-zoom">
                    <img src="Capture.PNG">
                </div>
            </div>

-->


<!--table navigation-->

<div class="container-fluid">
    <div class="row">
        <div class="col-3" style="background-color: #2C3E50 ">
            <div class="container-fluid" style="margin-top: 150px">
                <a href="{{url('/Emanagement') }}">
                    <table class="table" style="width:300px;height:100px; margin-left: 0px;margin-top:20px">
                        <thead class="thead-dark">
                        <tr class="btn" style="">
                            <th class="text-center" scope="row" style="width:300px ; height:10px">EMPLOYEE MANAGEMENT
                            </th>
                        </tr>

                        </thead>
                    </table>
                </a>

                <a href="{{url('/Eleave') }}">
                    <table class="table" style="width:300px ; margin-left: 0px ">
                        <thead class="thead-dark">
                        <tr class="btn">
                            <th class="text-center" scope="row" style="width:300px;height:10px">LEAVE MANAGEMENT</th>
                        </tr>
                        </thead>
                    </table>
                </a>
                <a href="{{url('/Eattendence')}}">
                    <table class="table" style="width:300px;margin-left: 0px">
                        <thead class="thead-dark">
                        <tr class="btn">
                            <th class="text-center" scope="row" style="width:300px;height:10px">DAILY ATTENDANCE</th>
                        </tr>
                        </thead>
                    </table>
                </a>

                <a href="{{url('/Esalary')}}">
                    <table class="table" style="width:300px;margin-left: 0px">
                        <thead class="thead-dark">
                        <tr class="btn">
                            <th class="text-center" scope="row" style="width:300px;height:10px">SALARY MANAGEMENT</th>
                        </tr>
                        </thead>
                    </table>
                    </table>
                </a>
            </div>

        </div>

        <div class="col-9">

            <div class="container-fluid" style="width: 900px ;margin-left: -3ch">
                <ul class="my">
                    <li class="my"><a href={{url('/Emanagement')}}><img
                                src="https://img.icons8.com/metro/26/000000/ingredients-list.png">All Employee</a></li>
                    <li class="my"><a href={{url('/Eadd')}}><img
                                src="https://img.icons8.com/metro/26/000000/add-user-male.png"> Add
                            Employee</a>
                    </li>
                    <li class="my"><a href="#contact"><img src="https://img.icons8.com/metro/26/000000/file.png">Report</a>
                    </li>
                </ul>
            </div>
            <div class="container">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="list-group">
                            @foreach ($errors->all() as $error)
                                <li style="margin-right: auto">{{ $error }}</li>
                        </ul>
                        @endforeach

                    </div>
                @endif
            </div>
            <p></p>

            <div class="table-wrapper-scroll-y my-custom-scrollbar">
                <div class="container-fluid" style="margin-top: 20px">
                    <form method="post" action="/edit" enctype="multipart/form-data">
                        {{csrf_field()}}


                        <div class="image-upload">
                            <label for="file-input">
                                <img src="https://img.icons8.com/wired/64/000000/add-image.png ">
                                <input type="file" onkeypress="return tabE(this,event)" name="image" id="image"/>
                            </label>
                        </div>
                        <p></p>
                        <div class="center">
                            <label for="exampleFormControlFile1"><b>Profile Picture</b></label>
                            <p></p>
                            <img class="avatar" src="{{ asset ('uploads/appsetting/'.$row->image) }}"
                                 style="margin-:100px"/>
                            <p></p>
                            <h3>Employee ID = {{$row->id}}</h3>
                        </div>
                        <p></p>

                        <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Registation No</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputEmail3" name="Rno" value="{{$row->id}}"
                                   readonly>
                        </div>

                        <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Employee Type</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputEmail3" name="type"
                                   value="{{$row->type}}" readonly>
                        </div>

                        <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Name</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputEmail3" name="name"
                                   value="{{$row->name}}">
                        </div>


                        <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Employee Category</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputEmail3"
                                   value="{{$row->category}}" readonly>
                            <select id="category" name="category" required="required" class="custom-select">
                                <option value="{{$row->category}}">{{$row->category}}</option>
                                <option value="Room Service">Room Service</option>
                                <option value="Kitchen Staff">Kitchen Staff</option>
                                <option value="Waitress">Waitress</option>
                            </select>
                        </div>

                        <p></p>

                        <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Date Of Birth</b></label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="inputEmail3" name="dob"
                                   value="{{$row->DOB}}">
                        </div>


                        <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Gender</b></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="inputEmail3"
                                   value="{{$row->gender}}" readonly>
                            <select id="category" name="gender" required="required" class="custom-select">
                                <option value="{{$row->gender}}">{{$row->gender}}</option>
                                <option value="Male">Male</option>
                                <option value="Femail">Female</option>
                            </select>
                            <p></p>

                            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Join Date</b></label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" id="inputEmail3" name="joindate" required
                                       value="{{$row->joindate}}">
                            </div>
                            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Salary</b></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputEmail3" name="salary" required
                                       value="{{$row->salary}}">
                            </div>

                            <hr size="2" color="black">

                            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Telephone No:</b></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputEmail3" placeholder="" name="tp"
                                       required
                                       value="{{$row->tp}}">
                            </div>


                            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Email</b></label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="" name="email"
                                       required
                                       value="{{$row->Email}}">
                            </div>

                            <hr size="2" color="black">

                            <label for="inputEmail3" class="col-sm-2 col-form-label"><b>Remark</b></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="inputEmail3" placeholder="" name="remark"
                                       value="{{$row->remark}}">
                            </div>


                            <div class="col-sm-8">
                                <input type="submit" class="btn btn-primary btn"
                                       style="margin-top:4px;margin-left: 440px"
                                       value="Update">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</body>

</html>