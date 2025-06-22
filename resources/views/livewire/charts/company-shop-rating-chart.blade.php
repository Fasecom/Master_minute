@once
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/locales/ru_RU.js"></script>
@endonce

@php $unit = $unit ?? '₽'; @endphp

<div wire:ignore id="{{ $chartId }}" class="w-full h-[500px]"></div>

<script>
    @php $fn = 'render_'.str_replace('-', '_', $chartId); @endphp

    function {{ $fn }}(newData = null){
        // Ensure amCharts is loaded
        if(!window.am5){ setTimeout(()=>{{ $fn }}(newData),100); return; }

        // Resolve data to plot
        const data = newData ?? @json($chartData);

        // Container and placeholder handling
        const container = document.getElementById("{{ $chartId }}");

        // If no data (all zero or empty) – show placeholder and stop
        const hasData = Array.isArray(data) && data.some(d => parseFloat(d.value || d.valueY || 0) > 0);
        if(!hasData){
            // Dispose previous chart instance if any
            if(window['root_{{ $chartId }}']){
                window['root_{{ $chartId }}'].dispose();
                delete window['root_{{ $chartId }}'];
            }
            container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400">Нет данных</div>';
            return;
        }

        // There is data – ensure container is clean from placeholder
        container.innerHTML = '';

        // Dispose previous root if exists
        if(window['root_{{ $chartId }}']){
            window['root_{{ $chartId }}'].dispose();
        }

        // Create new root
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

        // оси
        let xRenderer = window.am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
        xRenderer.labels.template.setAll({ rotation: -45, centerY: window.am5.p100, centerX: window.am5.p50, fontSize: 14 });
        let xAxis = chart.xAxes.push(window.am5xy.CategoryAxis.new(root, {
            renderer: xRenderer,
            categoryField: "category",
            tooltip: window.am5.Tooltip.new(root, { labelText: "{category}" })
        }));

        let yAxis = chart.yAxes.push(window.am5xy.ValueAxis.new(root, {
            renderer: window.am5xy.AxisRendererY.new(root, {}),
            min: 0,
        }));

        // Series
        let series = chart.series.push(window.am5xy.ColumnSeries.new(root, {
            name: "Revenue",
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "value",
            categoryXField: "category",
            tooltip: window.am5.Tooltip.new(root, { labelText: "{valueY.formatNumber('#,###.')} {{ $unit }}"})
        }));

        // градиент цвета от лучшего к худшему
        series.set("heatRules", [{
            target: series.columns.template,
            dataField: "valueY",
            key: "fill",
            min: window.am5.color(0x9ec6ff),
            max: window.am5.color(0x234E9B)
        }]);

        // такое же правило для stroke
        series.set("heatRules", [{
            target: series.columns.template,
            dataField: "valueY",
            key: "stroke",
            min: window.am5.color(0x9ec6ff),
            max: window.am5.color(0x234E9B)
        }]);

        // курсор для наведения
        let cursor = chart.set("cursor", window.am5xy.XYCursor.new(root, {
            behavior: "none",
            xAxis: xAxis,
            yAxis: yAxis,
        }));
        cursor.lineX.set("visible", false);
        cursor.lineY.set("visible", false);

        series.columns.template.setAll({
            width: window.am5.p70,
            fill: window.am5.color(0x234E9B), // базовый, будет переопределён heatRules
            stroke: window.am5.color(0x234E9B),
            cornerRadiusTR: 5,
            cornerRadiusTL: 5,
        });

        // эффект наведения: расширяем колонку
        series.columns.template.states.create("hover", {
            fillOpacity: 1,
            scaleX: 1.05,
            scaleY: 1.05,
        });

        // Set data
        xAxis.data.setAll(data);
        series.data.setAll(data);

        // Animate appearance
        series.appear(800);
        chart.appear(800, 50);
    }

    {{ $fn }}();
    window.addEventListener('updateChart', (e)=>{
        if(e.detail && e.detail.chartId === "{{ $chartId }}"){
            {{ $fn }}(e.detail.data);
        }
    });
    document.addEventListener('livewire:navigated', ()=> {{ $fn }}());
</script> 