<style>

  h3{
    text-align: center;
  }

  form.form-horizontal {
    margin-top: 25px;
}

td.update_emp {
    width: 10%;
}

td{
  width: 16%;
  text-align: right;

}

.emp-td-name{
  text-align: left;
}
legend {
    text-align: CENTER;
}
input#tipamount {
    width: 14%;
    text-align: center;
}
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}

.hours_percent {
    width: 100%;
    text-align: center;
}

.dpwrap {
width:100%;
  display: flex;
  flex-wrap: wrap;
  font-size: 14px;
}
#customers .td_time  {
  width: 40%;
}
.dpwrap span {
  width: 15%;
}
.export_button {
    width: 100%;
    text-align: center;
    margin-top: 50px;
}
button#button {
    background: #0d54f1;
    border: none;
    padding: 10px 45px;
    font-size: 20px;
    color: #fff;
}
button#button:hover{
  background: #1d64ff;

}
hr.hrline {
    margin-top: 50px;
}

.input-small{
  width: 30px;
    text-align: center;
}

select#level-select {
    height: 130px;
}

td.chnage_percent.green {
    background: #e2ffe2;
}
td.chnage_percent.red {
    background: #ffc8c8;
}
/* loader */
.loader {
  width: 215px;
  height: 215px;
  display: block;
  margin: auto;
  position: relative;
  background: #FFF;
  box-sizing: border-box;
}
.loader::after {
  content: '';
  width: calc(100% - 30px);
  height: calc(100% - 15px);
  top: 15px;
  left: 15px;
  position: absolute;
  background-image: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5) 50%, transparent 100%),
   linear-gradient(#DDD 100px, transparent 0),
   linear-gradient(#DDD 16px, transparent 0),
   linear-gradient(#DDD 50px, transparent 0);
  background-repeat: no-repeat;
  background-size: 75px 175px, 100% 100px, 100% 16px, 100% 30px;
  background-position: -185px 0, center 0, center 115px, center 142px;
  box-sizing: border-box;
  animation: animloader 1s linear infinite;
}

@keyframes animloader {
  to {
    background-position: 185px 0, center 0, center 115px, center 142px;
  }
}



</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous"> -->
 