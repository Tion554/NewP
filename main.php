<?php

function dijkstra(array $graph, string $startNode, string $endNode): array
{
    // Инициализация расстояний до всех вершин как бесконечность, кроме начальной
    $distances = array_fill_keys(array_keys($graph), INF);
    $distances[$startNode] = 0;

    // Инициализация массива предков для восстановления пути
    $previous = array_fill_keys(array_keys($graph), null);

    // Создаем множество непосещенных вершин
    $unvisited = array_keys($graph);

    while (!empty($unvisited)) {
        // Находим вершину с наименьшим расстоянием из непосещенных
        $current = null;
        $minDistance = INF;
        foreach ($unvisited as $node) {
            if ($distances[$node] < $minDistance) {
                $minDistance = $distances[$node];
                $current = $node;
            }
        }

        if ($current === null) {
            break; // Нет доступных вершин для дальнейшего посещения
        }

        // Если достигли конечной вершины, прекращаем
         if ($current === $endNode) {
            break;
        }

        // Удаляем текущую вершину из списка непосещенных
        $unvisited = array_diff($unvisited, [$current]);

        // Обновляем расстояния до соседних вершин
        foreach ($graph[$current] as $neighbor => $weight) {
            $newDistance = $distances[$current] + $weight;
            if ($newDistance < $distances[$neighbor]) {
                $distances[$neighbor] = $newDistance;
                $previous[$neighbor] = $current;
            }
        }
    }
     // Возвращаем расстояние и путь до конечной вершины
    return ['distance' => $distances[$endNode], 'path' => reconstructPath($previous, $endNode)];
}

// Функция восстановления пути
function reconstructPath(array $previous, string $endNode): array
{
    $path = [];
    $current = $endNode;

    while ($current !== null) {
        array_unshift($path, $current); // Добавляем в начало массива
        $current = $previous[$current];
    }

    return $path;
}

// Пример графа (ключ - вершина, значение - массив соседних вершин с весами)
$graph = [
    'A' => ['B' => 1, 'C' => 4],
    'B' => ['A' => 1, 'C' => 2, 'D' => 5],
    'C' => ['A' => 4, 'B' => 2, 'D' => 1, 'E' => 3],
    'D' => ['B' => 5, 'C' => 1, 'E' => 2],
    'E' => ['C' => 3, 'D' => 2]
];

$startNode = 'A';
$endNode = 'E';

$result = dijkstra($graph, $startNode, $endNode);

if ($result['distance'] !== INF) {
     echo "Кратчайший путь от $startNode до $endNode:\n";
    echo "Путь: " . implode(' -> ', $result['path']) . "\n";
    echo "Расстояние: " . $result['distance'] . "\n";
} else {
    echo "Нет пути от $startNode до $endNode\n";
}


?>
