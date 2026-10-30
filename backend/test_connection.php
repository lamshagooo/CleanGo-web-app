<?php

require_once __DIR__ . '/dao/UserDao.php';
require_once __DIR__ . '/dao/CleanerProfileDao.php';
require_once __DIR__ . '/dao/ServiceDao.php';
require_once __DIR__ . '/dao/CleanerServiceDao.php';
require_once __DIR__ . '/dao/BookingDao.php';
require_once __DIR__ . '/dao/ReviewDao.php';

echo "\n=== CLEANGO FINAL CRUD TEST ===\n";

$userDao = new UserDao();
$cleanerDao = new CleanerProfileDao();
$serviceDao = new ServiceDao();
$cleanerServiceDao = new CleanerServiceDao();
$bookingDao = new BookingDao();
$reviewDao = new ReviewDao();

try {

    // ========================================
    // 1. USER DAO
    // ========================================

    echo "\n--- USER DAO ---\n";

    $userId = $userDao->insert([
        'name' => 'Test User',
        'email' => 'test_' . uniqid() . '@example.com',
        'password' => password_hash('TestPassword123!', PASSWORD_DEFAULT),
        'role' => 'customer'
    ]);

    echo "CREATE: OK\n";

    $user = $userDao->getById($userId);

    if (!$user) {
        throw new Exception("User READ failed.");
    }

    echo "READ: OK\n";

    $userDao->update($userId, [
        'name' => 'Updated Test User'
    ]);

    $user = $userDao->getById($userId);

    if ($user['name'] !== 'Updated Test User') {
        throw new Exception("User UPDATE failed.");
    }

    echo "UPDATE: OK\n";

    $userDao->delete($userId);

    if ($userDao->getById($userId) !== false) {
        throw new Exception("User DELETE failed.");
    }

    echo "DELETE: OK\n";


    // ========================================
    // 2. CLEANER PROFILE DAO
    // ========================================

    echo "\n--- CLEANER PROFILE DAO ---\n";

    $cleanerUserId = $userDao->insert([
        'name' => 'Test Cleaner',
        'email' => 'cleaner_' . uniqid() . '@example.com',
        'password' => password_hash('TestPassword123!', PASSWORD_DEFAULT),
        'role' => 'cleaner'
    ]);

    $cleanerId = $cleanerDao->insert([
        'user_id' => $cleanerUserId,
        'bio' => 'Test cleaner profile',
        'hourly_rate' => 20,
        'location' => 'Sarajevo',
        'experience_years' => 2
    ]);

    echo "CREATE: OK\n";

    $cleaner = $cleanerDao->getById($cleanerId);

    if (!$cleaner) {
        throw new Exception("Cleaner READ failed.");
    }

    echo "READ: OK\n";

    $cleanerDao->update($cleanerId, [
        'hourly_rate' => 25
    ]);

    $cleaner = $cleanerDao->getById($cleanerId);

    if ((float)$cleaner['hourly_rate'] !== 25.0) {
        throw new Exception("Cleaner UPDATE failed.");
    }

    echo "UPDATE: OK\n";

    $cleanerDao->delete($cleanerId);
    $userDao->delete($cleanerUserId);

    if ($cleanerDao->getById($cleanerId) !== false) {
        throw new Exception("Cleaner DELETE failed.");
    }

    echo "DELETE: OK\n";


    // ========================================
    // 3. BOOKING DAO
    // ========================================

    echo "\n--- BOOKING DAO ---\n";

    // Use existing sample data:
    // user 3, cleaner 1, service 1

    $bookingId = $bookingDao->insert([
        'user_id' => 3,
        'cleaner_id' => 1,
        'service_id' => 1,
        'booking_date' => '2027-01-15',
        'booking_time' => '10:00:00',
        'duration' => 2,
        'status' => 'pending'
    ]);

    echo "CREATE: OK\n";

    $booking = $bookingDao->getById($bookingId);

    if (!$booking) {
        throw new Exception("Booking READ failed.");
    }

    echo "READ: OK\n";

    $bookingDao->update($bookingId, [
        'status' => 'confirmed'
    ]);

    $booking = $bookingDao->getById($bookingId);

    if ($booking['status'] !== 'confirmed') {
        throw new Exception("Booking UPDATE failed.");
    }

    echo "UPDATE: OK\n";


    // ========================================
    // 4. REVIEW DAO
    // ========================================

    echo "\n--- REVIEW DAO ---\n";

    $reviewId = $reviewDao->insert([
        'booking_id' => $bookingId,
        'rating' => 5,
        'comment' => 'Test review'
    ]);

    echo "CREATE: OK\n";

    $review = $reviewDao->getById($reviewId);

    if (!$review) {
        throw new Exception("Review READ failed.");
    }

    echo "READ: OK\n";

    $reviewDao->update($reviewId, [
        'rating' => 4,
        'comment' => 'Updated test review'
    ]);

    $review = $reviewDao->getById($reviewId);

    if ((int)$review['rating'] !== 4) {
        throw new Exception("Review UPDATE failed.");
    }

    echo "UPDATE: OK\n";

    $reviewDao->delete($reviewId);

    if ($reviewDao->getById($reviewId) !== false) {
        throw new Exception("Review DELETE failed.");
    }

    echo "DELETE: OK\n";


    // ========================================
    // 5. DELETE BOOKING
    // ========================================

    $bookingDao->delete($bookingId);

    if ($bookingDao->getById($bookingId) !== false) {
        throw new Exception("Booking DELETE failed.");
    }

    echo "\nBOOKING DELETE: OK\n";

    echo "\n=== ALL CRUD TESTS PASSED ===\n";

} catch (Exception $e) {

    echo "\nTEST FAILED: " . $e->getMessage() . "\n";
}