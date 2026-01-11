<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['admin_email'])){
    echo "<script>window.open('login.php','_self')</script>";
    exit;
}
?>

<div class="row">
    <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
        <div class="breadcrumb">
            <li class="active">
                <i class="fa fa-fw fa-cogs"></i>
                Admin Control / Financial Year
            </li>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading corporate-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-calendar-o"></i> Change Financial Year
                </h3>
            </div>

            <div class="panel-body" style="padding-top:20px;">

                <!-- ✅ FORM ADDED -->
                <form method="post">

                    <div class="form-group">
                        <label class="col-md-3 control-label" style="padding-top: 5px;">Select Finance Year :</label>

                        <div class="col-md-3">
                            <select name="financial_year" class="form-control" required>
                                <option value="">-- Select Financial Year --</option>
                                <?php
                                $query = "SELECT * FROM fyear_list WHERE start_date > CURDATE() ORDER BY fy_id";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="'.$row['fy_id'].'">'.$row['display_fyear'].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit"
                                    name="submit"
                                    class="btn btn-back">
                                <i class="fa fa-exchange"></i> Change
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-10 col-sm-12 col-xs-12 mx-auto">
        <div class="panel panel-primary">

            <!-- CURRENT FINANCIAL YEAR -->
            <div class="panel-heading corporate-heading">
                <h3 class="panel-title"><i class="fa fa-fw fa-calendar-o"></i>
                    Current&nbsp;&nbsp;Financial&nbsp;&nbsp;Year
                </h3>
            </div>

            <div class="table-responsive" style="padding-top: 3px;">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Finance Year</th>
                            <th class="text-center">Start Date</th>
                            <th class="text-center">End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $query = "SELECT * FROM finance_year WHERE status='1'";
                    $result = mysqli_query($con, $query);
                    if(mysqli_num_rows($result) > 0){
                        while($row2 = mysqli_fetch_assoc($result)){
                    ?>
                        <tr>
                            <td class="text-center"><?= $row2['f_year']; ?></td>
                            <td class="text-center"><?= date("d-m-Y", strtotime($row2['start_date'])); ?></td>
                            <td class="text-center"><?= date("d-m-Y", strtotime($row2['end_date'])); ?></td>
                        </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center text-danger'>No Records Found</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
if(isset($_POST['submit'])){

    mysqli_begin_transaction($con);

    $fy_id = $_POST['financial_year'];

    $fy = mysqli_fetch_assoc(mysqli_query($con,
        "SELECT * FROM fyear_list WHERE fy_id='$fy_id'"
    ));

    $fyear = $fy['fyear'];
    $start_date = $fy['start_date'];
    $end_date = $fy['end_date'];

    mysqli_query($con,"UPDATE finance_year SET status=0 WHERE status=1");

    if(mysqli_query($con,"
        INSERT INTO finance_year (f_year,start_date,end_date,status)
        VALUES ('$fyear','$start_date','$end_date',1)
    ")){

        $check = mysqli_num_rows(mysqli_query($con,
            "SELECT * FROM booking_counter WHERE fyear='$fyear'"
        ));

        if($check > 0){
            mysqli_rollback($con);
            $msg = "Booking Counter already exists for this year!";
            $type = "error";
        } else {
            mysqli_query($con,"INSERT INTO booking_counter (current_no,fyear) VALUES (0,'$fyear')");
            mysqli_commit($con);
            $msg = "Financial Year changed successfully!";
            $type = "success";
        }

    } else {
        mysqli_rollback($con);
        $msg = "Something went wrong!";
        $type = "error";
    }

    echo "
    <script>
        Swal.fire({
            icon: '$type',
            title: '".($type=='success'?'Success':'Error')."',
            text: '$msg',
            confirmButtonColor: '#7B1E2B'
        }).then(()=>{
            window.location.href='index.php?change_Finance_Year';
        });
    </script>";
}
?>
