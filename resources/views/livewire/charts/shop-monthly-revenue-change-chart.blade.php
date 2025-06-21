@once
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/locales/ru_RU.js"></script>
@endonce

<div wire:ignore id="{{ $chartId }}" class="w-full h-[500px]"></div>

<script>
    @php $fn = 'render_'.str_replace('-', '_', $chartId); @endphp

    function {{ $fn }}(newData = null){
        if(!window.am5){ setTimeout(()=>{{ $fn }}(newData),100); return; }

        const data = newData ?? @json($chartData);
        const container = document.getElementById("{{ $chartId }}");
        const hasData = Array.isArray(data) && data.some(d => parseFloat(d.value || 0) !== 0);

        if(!hasData){
            if(window['root_{{ $chartId }}']){
                window['root_{{ $chartId }}'].dispose();
                delete window['root_{{ $chartId }}'];
            }
            container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400">Нет данных</div>';
            return;
        }

        container.innerHTML = '';
        if(window['root_{{ $chartId }}']){
            window['root_{{ $chartId }}'].dispose();
        }

        let root = window.am5.Root.new("{{ $chartId }}");
        window['root_{{ $chartId }}'] = root;
        root.setThemes([ window.am5themes_Animated.new(root) ]);
        root.locale = window.am5locales_ru_RU;

        let chart = root.container.children.push(window.am5xy.XYChart.new(root, {
            panX: false,
            panY: false,
            wheelX: "none",
            wheelY: "none",
        }));

        let xRenderer = window.am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
        xRenderer.labels.template.setAll({ rotation: -45, centerY: window.am5.p100, centerX: window.am5.p50, fontSize: 14 });
        let xAxis = chart.xAxes.push(window.am5xy.CategoryAxis.new(root, {
            renderer: xRenderer,
            categoryField: "category",
            tooltip: window.am5.Tooltip.new(root, { labelText: "{category}" })
        }));

        let yAxis = chart.yAxes.push(window.am5xy.ValueAxis.new(root, {
            renderer: window.am5xy.AxisRendererY.new(root, {}),
            extraMin: 0.1,
            extraMax: 0.1,
        }));

        let series = chart.series.push(window.am5xy.ColumnSeries.new(root, {
            name: "Изменение",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            categoryXField: "category",
            tooltip: window.am5.Tooltip.new(root, { labelText: "{valueY.formatNumber('#,###.')} ₽" })
        }));

        series.columns.template.setAll({
            width: window.am5.p70,
            fill: window.am5.color(0x234E9B),
            stroke: window.am5.color(0x234E9B),
            strokeOpacity: 0,
            cornerRadiusTR: 5,
            cornerRadiusTL: 5,
        });

        series.columns.template.adapters.add("fill", (fill, target) => {
            return (target.dataItem?.dataContext?.value || 0) >= 0 ? window.am5.color(0x234E9B) : window.am5.color(0xE31E24);
        });
        series.columns.template.adapters.add("stroke", (stroke, target) => {
            return (target.dataItem?.dataContext?.value || 0) >= 0 ? window.am5.color(0x234E9B) : window.am5.color(0xE31E24);
        });

        series.columns.template.states.create("hover", {
            fillOpacity: 1,
            scaleX: 1.05,
            scaleY: 1.05,
        });

        let cursor = chart.set("cursor", window.am5xy.XYCursor.new(root, {
            behavior: "none",
            xAxis: xAxis,
            yAxis: yAxis,
        }));
        cursor.lineX.set("visible", false);
        cursor.lineY.set("visible", false);

        xAxis.data.setAll(data);
        series.data.setAll(data);

        series.appear(800);
        chart.appear(800, 50);
    }

    {{ $fn }}();
    window.addEventListener('updateChart', (e) => {
        if(e.detail && e.detail.chartId === "{{ $chartId }}"){
            {{ $fn }}(e.detail.data);
        }
    });
    document.addEventListener('livewire:navigated', () => {{ $fn }}());
</script> 