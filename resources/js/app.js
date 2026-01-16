import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Helper function to get CSS custom property value with fallback
const getCSSVariable = (name, fallback) => {
    const value = getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    return value || fallback;
};

// Theme colors for charts (read from CSS custom properties)
// Uses Deep Indigo (#3E3B92) as primary and Soft Lavender (#E6E6FA) as empty
const chartTheme = {
    primary: () => getCSSVariable('--progress-default', '#3E3B92'),
    empty: () => getCSSVariable('--progress-empty', '#E6E6FA'),
};

// Alpine.js component for circular progress charts
Alpine.data('progressChart', (percentage, label = '', color = null) => ({
    percentage: percentage,
    label: label,
    color: color || chartTheme.primary(),
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
                    backgroundColor: [this.color, chartTheme.empty()],
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
