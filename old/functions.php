<?php

function logMessage($msg)
{
    echo "<div style='font-family:monospace'>" . htmlspecialchars($msg) . "</div>";
    @ob_flush();
    flush();
}

function randomReactions($oxunub)
{
    $like = $oxunub > 0 ? rand($oxunub * 0.4, $oxunub * 0.8) : 0;
    $dislike = $like > 0 ? rand(0, $like * 0.3) : 0;

    return [
        "like" => $like,
        "dislike" => $dislike,
        "love" => rand(0, $like * 0.2),
        "funny" => rand(0, $like * 0.1),
        "wow" => rand(0, $like * 0.15),
        "sad" => rand(0, $dislike * 0.5),
        "angry" => rand(0, $dislike * 0.3)
    ];
}

function progress($done,$total)
{
    $percent = $total>0 ? floor(($done/$total)*100) : 0;

    echo "
    <script>
        document.getElementById('bar').style.width='{$percent}%';
        document.getElementById('bar').innerText='{$percent}%';
    </script>
    ";

    @ob_flush();
    flush();
}
?>