# Disk C: usage via CIM (robust for non-interactive hosts/IIS sessions)
$disk = Get-CimInstance -ClassName Win32_LogicalDisk -Filter "DeviceID='C:'"

$free  = [Math]::Round($disk.FreeSpace / 1GB, 1)
$total = [Math]::Round($disk.Size / 1GB, 1)
$used  = [Math]::Round(($disk.Size - $disk.FreeSpace) / 1GB, 1)
$pct   = [Math]::Round((($disk.Size - $disk.FreeSpace) / $disk.Size) * 100, 0)

@{
    diskUsedGB  = $used
    diskFreeGB  = $free
    diskTotalGB = $total
    diskPct     = $pct
} | ConvertTo-Json -Compress
