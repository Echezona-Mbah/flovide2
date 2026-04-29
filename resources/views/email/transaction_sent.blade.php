<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Flovide — Transfer موفق</title>
</head>
<body style="margin:0; padding:0; background:#eef2f8; font-family: 'Segoe UI', Arial, sans-serif; color:#0f172a;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef2f8; padding:32px 0;">
    <tr>
      <td align="center">

        <!-- Wrapper -->
        <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="background:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 18px 40px rgba(15,23,42,0.12);">

          <!-- Header -->
          <tr>
            <td style="padding:0;">
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td style="background:linear-gradient(135deg,#0ea5e9,#2563eb); padding:30px;">
                    <img src="{{ asset('Logo.png') }}" alt="Flovide Logo" style="max-width:140px; display:block; margin-bottom:16px;">
                    <div style="font-size:12px; color:#dbeafe; letter-spacing:2px; text-transform:uppercase;">Flovide Transfer</div>
                    <h1 style="margin:8px 0 6px; font-size:22px; font-weight:700; color:#ffffff;">Money Sent Successfully</h1>
                    <span style="display:inline-block; background:#22c55e; color:#ffffff; font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px;">
                      Completed
                    </span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:30px 32px 20px;">
              <p style="margin:0 0 10px; font-size:15px; color:#1e293b;">Hello {{ $name }},</p>
              <p style="margin:0 0 18px; font-size:15px; color:#475569;">
                Your transfer was processed successfully. Here’s a clear summary of your transaction.
              </p>

              <!-- Highlight Card -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:18px;">
                <tr>
                  <td style="font-size:13px; color:#64748b;">Amount Sent</td>
                  <td align="right" style="font-size:16px; font-weight:700;">{{ $amount_sent }} {{ $sending_currency }}</td>
                </tr>
                <tr>
                  <td colspan="2" style="padding:6px 0;"></td>
                </tr>
                <tr>
                  <td style="font-size:13px; color:#64748b;">Recipient Receives</td>
                  <td align="right" style="font-size:15px; font-weight:600;">{{ $recipient_amount }} {{ $recipient_currency }}</td>
                </tr>
                <tr>
                  <td style="font-size:13px; color:#64748b;">Transfer Fee</td>
                  <td align="right" style="font-size:14px; font-weight:600;">{{ $fee }} {{ $sending_currency }}</td>
                </tr>
                <tr>
                  <td style="font-size:13px; color:#64748b;">Total Debited</td>
                  <td align="right" style="font-size:15px; font-weight:700; color:#0f172a;">{{ $total_amount }} {{ $sending_currency }}</td>
                </tr>
                <tr>
                  <td style="font-size:13px; color:#64748b;">Current Balance</td>
                  <td align="right" style="font-size:14px; font-weight:600;">{{ $current_balance }} {{ $sending_currency }}</td>
                </tr>
                <tr>
                  <td style="font-size:13px; color:#64748b;">Reference</td>
                  <td align="right" style="font-size:13px; font-weight:600;">{{ $reference }}</td>
                </tr>
              </table>

              <!-- CTA -->
              <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:22px;">
                <tr>
                  <td align="center">
                    <a href="#" style="background:#2563eb; color:#ffffff; text-decoration:none; font-size:14px; font-weight:600; padding:12px 22px; border-radius:10px; display:inline-block;">
                      View Transaction History
                    </a>
                  </td>
                </tr>
              </table>

              <p style="margin:18px 0 0; font-size:13px; color:#64748b; text-align:center;">
                Need help? Reply to this email and the Flovide team will assist you.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td align="center" style="background:#f1f5f9; padding:14px; font-size:12px; color:#94a3b8;">
              © {{ date('Y') }} Flovide. All rights reserved.
            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</body>
</html>
