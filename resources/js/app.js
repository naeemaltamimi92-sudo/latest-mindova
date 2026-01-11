import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Alpine.js component for circular progress charts
Alpine.data('progressChart', (percentage, label = '', color = '#0284c7') => ({
    percentage: percentage,
    label: label,
    color: color,
    chart: null,

    init() {
        this.createChart();
    },

    createChart() {
        const canvas = this.$refs.canvas;
        if (!canvas) return;

        const ctx = canvas.getContext('2d');

        this.chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [this.percentage, 100 - this.percentage],
                    backgroundColor: [this.color, '#e5e7eb'],
                    borderWidth: 0,
                    circumference: 360,
                    rotation: -90
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            }
        });
    },

    updateProgress(newPercentage) {
        this.percentage = newPercentage;
        if (this.chart) {
            this.chart.data.datasets[0].data = [newPercentage, 100 - newPercentage];
            this.chart.update();
        }
    }
}));

Alpine.start();
