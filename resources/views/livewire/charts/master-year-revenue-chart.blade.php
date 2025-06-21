@once
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/locales/ru_RU.js"></script>
@endonce

<div wire:ignore id="{{ $chartId }}" class="w-full h-[300px]"></div>

<script>
    @php
        $fn = 'render_'.str_replace('-', '_', $chartId);
    @endphp
    function {{ $fn }}(){
        if(!window.am5){ setTimeout({{ $fn }},100); return; }
        // dispose previous root if exists
        if(window['root_{{ $chartId }}']){
            window['root_{{ $chartId }}'].dispose();
        }
        let root = window.am5.Root.new("{{ $chartId }}");
        window['root_{{ $chartId }}']=root;
        root.setThemes([ window.am5themes_Animated.new(root) ]);
        root.locale = window.am5locales_ru_RU;
        root.dateFormatter.setAll({
            dateFormat: "MMM",
            dateFields: ["valueX"]
        });
        let chart = root.container.children.push(window.am5xy.XYChart.new(root, {}));

        let xRenderer = window.am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
        let xAxis = chart.xAxes.push(window.am5xy.DateAxis.new(root, {
            baseInterval: { timeUnit: "month", count: 1 },
            groupData: false,
            renderer: xRenderer,
        }));

        let yRenderer = window.am5xy.AxisRendererY.new(root, {});
        let yAxis = chart.yAxes.push(window.am5xy.ValueAxis.new(root, {
            renderer: yRenderer,
        }));

        let series = chart.series.push(window.am5xy.LineSeries.new(root, {
            name: "Выручка",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            valueXField: "date",
            stroke: window.am5.color(0x234E9B),
            tooltip: window.am5.Tooltip.new(root, { labelText: "{valueY}" })
        }));

        xAxis.data.setAll(@json($chartData));
        console.log('masterYearData', @json($chartData));
        series.strokes.template.setAll({ strokeWidth: 3, stroke: window.am5.color(0x234E9B) });
        series.data.setAll(@json($chartData));

        let cursor = chart.set("cursor", window.am5xy.XYCursor.new(root, {
            xAxis: xAxis,
            yAxis: yAxis,
            behavior: "none"
        }));
        cursor.lineY.set("visible", false);

        series.bullets.push(function(){
            const circle = window.am5.Circle.new(root, {
                radius: 4,
                fill: series.get("stroke"),
            });
            circle.states.create("hover", { scale: 1.5 });
            return window.am5.Bullet.new(root, { sprite: circle });
        });
    }
    // первый вызов
    {{ $fn }}();
    document.addEventListener('livewire:navigated', {{ $fn }});
</script> 