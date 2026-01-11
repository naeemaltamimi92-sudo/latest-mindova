#!/bin/bash

echo "========================================"
echo "  Guided Tour System - Deployment"
echo "========================================"
echo ""

echo "[1/5] Running database migration..."
php artisan migrate --path=database/migrations/2025_12_23_204148_create_user_guidance_progress_table.php
echo ""

echo "[2/5] Clearing configuration cache..."
php artisan config:clear
echo ""

echo "[3/5] Clearing view cache..."
php artisan view:clear
echo ""

echo "[4/5] Clearing application cache..."
php artisan cache:clear
echo ""

echo "[5/5] Clearing route cache..."
php artisan route:clear
echo ""

echo "========================================"
echo "  Deployment Complete!"
echo "========================================"
echo ""
echo "Next steps:"
echo "1. Add element IDs to your Blade templates (see GUIDED_FLOW_COMPLETE_IMPLEMENTATION.md)"
echo "2. Create test accounts (volunteer and company)"
echo "3. Walk through the complete user flows"
echo "4. Use resetGuidance() in browser console to test again"
echo ""
echo "Testing Commands:"
echo "- Browser Console: resetGuidance() - Reset all guidance progress"
echo "- Browser Console: window.GuidedTour.currentSteps - Check active steps"
echo "- Browser Console: window.GuidedTour.dismissAll() - Dismiss all tooltips"
echo ""
