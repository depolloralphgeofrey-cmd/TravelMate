<?php
// ===== TravelMate monetization: Google AdMob ads, reward points, individual features, discounts =====
// Figures follow the business plan (Chapter V Table 8, Chapter VI "Advertisements through Google AdMob",
// and the Discount Protocols).
require_once __DIR__ . '/matching.php';

// ---- Google AdMob ------------------------------------------------------------------------------
// NOTE: AdMob is Google's *mobile app* ad platform (Android/iOS SDK). On this PHP website the ads are
// simulated with demo creatives. When the Travel Buddy mobile app is built, replace these with your own
// AdMob IDs (admob.google.com) and use the AdMob Interstitial + Rewarded ad units.
// The IDs below are Google's public TEST IDs, safe for development.
define('ADMOB_APP_ID',            'ca-app-pub-3940256099942544~3347511713');
define('ADMOB_INTERSTITIAL_UNIT', 'ca-app-pub-3940256099942544/1033173712');
define('ADMOB_REWARDED_UNIT',     'ca-app-pub-3940256099942544/5224354917');

// ---- Rewarded ads & points (paper: 5 points per completed ad, max 5 ads/day = 25 points = P10) ----
const AD_POINTS        = 5;      // points per completed rewarded ad
const AD_DAILY_MAX     = 5;      // rewarded ads per day
const POINTS_PER_PESO  = 2.5;    // 25 points = P10
const AD_WATCH_SECONDS = 5;      // demo length (real rewarded ads run ~15-30 s)
const AD_ECPM          = 50;     // illustrative eCPM in pesos (paper: P50 per 1,000 impressions)

// ---- Pop-up (interstitial) ads for free users ----
const POPUP_DELAY_SEC  = 20;     // first pop-up after this many seconds on a page
const POPUP_GAP_SEC    = 180;    // minimum seconds between pop-ups

// ---- Individual premium features (paper: P70 per feature) ----
const FEATURE_PRICE       = 70;
const FEATURE_DAYS_PAID   = 30;  // assumption: a purchased feature lasts 30 days
const FEATURE_DAYS_POINTS = 7;   // assumption: points give temporary access (7 days)
function feature_points_cost() { return (int)ceil(FEATURE_PRICE * POINTS_PER_PESO); }  // P70 = 175 points

// ---- Trip estimate defaults (used by "Let's Travel Now"): per person, by budget tier ----
const TRIP_PER_PERSON = ['Budget' => 17500, 'Moderate' => 37500, 'Luxury' => 60000];
const BOOKING_COMMISSION = 10;   // % commission TravelMate earns from partner establishments (paper)

function premium_features() {
    // key => [name, icon, description, available on this website demo?]
    return [
        'feedback'   => ['Feedback Visibility', '📝', 'See feedback from other travelers before you connect with them.', true],
        'recommend'  => ['Advanced Personalized Recommendations', '🎯', 'See the detailed compatibility breakdown for every traveler.', true],
        'search'     => ['Advanced Traveler Search', '🔎', 'Filter matches by minimum match %, budget, and shared interests.', true],
        'visibility' => ['Enhanced Profile Visibility', '🚀', 'Your profile is boosted so more travelers see it.', true],
        'nearby'     => ['Nearby Traveler Access', '📍', 'Find compatible travelers near your destination (needs phone GPS).', false],
        'location'   => ['Unlimited Location Control', '🌐', 'Change search locations without limits (mobile app).', false],
    ];
}

function feature_until($key, $rec = null) {
    $rec = $rec ?? user_rec();
    $u = $rec['features'][$key] ?? '';
    return ($u && strtotime($u) > time()) ? $u : '';
}
function feature_active($key, $rec = null) {
    $rec = $rec ?? user_rec();
    return is_premium($rec) || feature_until($key, $rec) !== '';
}

// ---- Ads helpers ----
function show_ads($rec = null) {
    if (!is_logged_in()) return false;
    $rec = $rec ?? user_rec();
    return !is_premium($rec);               // Premium = no ads
}
function ads_today($rec) {
    $d = $rec['ads'] ?? [];
    return (($d['date'] ?? '') === date('Y-m-d')) ? (int)($d['count'] ?? 0) : 0;
}
function points_balance($rec) { return (int)($rec['points'] ?? 0); }
function points_to_peso($pts) { return round($pts / POINTS_PER_PESO, 2); }

// ---- Discount protocol (paper): New User 10% on first purchase; Student 20%. Best one applies. ----
function discount_for($rec, $student = false) {
    $best = ['label' => '', 'pct' => 0];
    if (empty($rec['payments'])) { $best = ['label' => 'New User Discount (first purchase)', 'pct' => 10]; }
    if ($student && $best['pct'] < 20) { $best = ['label' => 'Student Traveler Discount (subject to ID verification)', 'pct' => 20]; }
    return $best;
}
