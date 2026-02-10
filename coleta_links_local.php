<?php

$url = "https://news.ycombinator.com/";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");
$html = curl_exec($ch);
curl_close($ch);

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

$rows = $dom->getElementsByTagName("tr");

$resultado = [];

foreach ($rows as $row) {
    $links = $row->getElementsByTagName("a");

    foreach ($links as $a) {
        $texto = trim($a->textContent);
        $href = $a->getAttribute("href");

        if (strlen($texto) > 20 && str_starts_with($href, "http")) {
            $resultado[] = [$texto, $href];
        }
    }
}

$file = fopen("coleta_links.csv", "w");
fputcsv($file, ["titulo", "link"]);

foreach ($resultado as $linha) {
    fputcsv($file, $linha);
}

fclose($file);

echo "ok\n";
