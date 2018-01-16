<h1>Profiler</h1>

<table>
    <tr>
        <th>Function</th>
        <th>Calls</th>
        <th>Time Inclusive</th>
        <th>Memory Inclusive</th>
        <th>Time own</th>
        <th>Memory own</th>
    </tr>
    <?php foreach ($functions as $name => $f): ?>
        <tr>
            <td><?=$name?></td>
            <td><?=sprintf("%5d", $f['calls'])?></td>
            <td><?=sprintf("%3.4f", $f['time-inclusive'])?></td>
            <td><?=sprintf("%8d", $f['memory-inclusive'])?></td>
            <td><?=sprintf("%3.4f", $f['time-own'])?></td>
            <td><?=sprintf("%8d", $f['memory-own'])?></td>
        </tr>
    <?php endforeach; ?>
</table>
