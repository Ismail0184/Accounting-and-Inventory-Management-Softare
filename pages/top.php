<?php require_once 'support_file.php';?>
    <div class="nav_menu" style="background-color:#337ab7;">
        <nav style=" width:100%;">
            <div class="nav toggle" style="width:30%;">
                <a id="menu_toggle"><i class="fa fa-bars" style="color:<?=$_SESSION[logo_color]?>"></i> <span style="margin-left:10px;margin-buttom:100px;position: relative;
top: -4px; font-size:15px;font-weight:bold; color:white"><?=strtoupper($_SESSION[company_name])?></snap></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li class="">
                  
                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?=$_SESSION['userpic'];?>" alt="" style="height:30px; width:30px;border: 1px solid <?=$_SESSION[logo_color]?>;
    border-radius: 25px; background-color:#069;"> <span style="color:white"><?=$_SESSION['username'];?></span>
                         <span class="fa fa-angle-down"></span>
                    </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                  <li><a href="profile.php"> Profile</a></li>
                      <li><a href="account_settings.php"> Change Password</a></li>
                      <li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"  style="color:white"></i>
                    <span class="badge bg-green">1</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="http://icpbd-erp.com/51816/hrm_mod/pic/staff/61.jpeg" alt="Profile Image" /></span>
                        <span>
                          <span>Md Ismail Hossain</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">This notification bar is under construction.</span>
                      </a>
                    </li>
                  
              
                  
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Notifications</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
                <li role="presentation" class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                        <?php if($_SESSION['language']=='Bangla') {?>
                            <image src="../assets/images/icon/bn.png" height="15" weight="15"></image>
                        <?php } else if($_SESSION['language']=='English') {?>
                            <image src="../assets/images/icon/en.png" height="15" weight="15"></image>
                        <?php } ?>

                    </a>
                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        <li><image src="../assets/images/icon/en.png" height="25" weight="25"><a href="dashboard.php?language=English">English</a></li>
                        <li><image src="../assets/images/icon/bn.png" height="25" weight="25"><a href="dashboard.php?language=Bangla"">Bangla</a></li>
                    </ul>
                </li>
              </ul>
        </nav>
    </div>
