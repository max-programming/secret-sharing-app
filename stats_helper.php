<?php

declare(strict_types=1);

/**
 * Increment the created secrets counter in the stats table
 */
function incrementCreatedSecrets($conn): void
{
  $sql = "UPDATE stats SET created_secrets = created_secrets + 1 WHERE id = 1";
  $conn->query($sql);
}

/**
 * Increment the destroyed secrets counter in the stats table
 */
function incrementDestroyedSecrets($conn): void
{
  $sql = "UPDATE stats SET destroyed_secrets = destroyed_secrets + 1 WHERE id = 1";
  $conn->query($sql);
}

/**
 * Get the current stats from the database
 * Returns an associative array with 'created_secrets' and 'destroyed_secrets'
 */
function getStats($conn): array
{
  $sql = "SELECT created_secrets, destroyed_secrets FROM stats WHERE id = 1";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0) {
    return $result->fetch_assoc();
  }

  // Return default values if stats row doesn't exist
  return ['created_secrets' => 0, 'destroyed_secrets' => 0];
}
