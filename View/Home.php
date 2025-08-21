<?php 
session_start();
$nome = $_SESSION['usuario_nome'] ?? 'Usuário';
$perfil = $_SESSION['usuario_tipo'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../style/Usuario/perfil.css">
    <title>Perfil</title>
</head>

<body>
    <section id="perfil">
        <div class="perfil-container">
            <div class="perfil-info">
                <div id="nome-container">
                    <h2>Usuário</h2>
                    <p><span id="nome-usuario"><?php echo $nome?></span></p>
                </div>
            </div>
            <div class="foto-perfil"></div>
        </div>
        <div id="menu-graficos">
            <div class="menu">
                <h2><i class="fas fa-bars"></i> Menu</h2>
                <ul>
                    <li><a href="Menu.php"><i class="fas fa-chart-line"></i>Tarefas</a></li>
                    <!-- <li><a href="#"><i class="fas fa-user-edit"></i> Editar perfil</a></li> -->
                    <li><a href="../index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
            <div class="graficos">
                <h2><i class="fas fa-chart-pie"></i> Gráficos</h2>
                <div id="donutchart" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
        </div>
    </section>
</body>
<script type="text/javascript">
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Completas', 11],
            ['Incopletas', 2]
        ]);

        var options = {
            title: 'Atividades completas na semana',
            pieHole: 0.4,
            backgroundColor: '#1e1e1e',
            titleTextStyle: {
                color: '#ffffffff'
            },
            legend: {
                textStyle: {
                    color: '#cccccc'
                }
            },
            pieSliceTextStyle: {
                color: '#000000'
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>

</html>