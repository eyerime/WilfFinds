<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login - Wildcats Lost & Found</title>
    <link rel="stylesheet" href="../../assets/style.css?v=3">
    <style>
        /* Isolate login page styles so it doesn't break dashboards */
        body, html {
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden; /* Prevents scrolling on the login screen */
            background: #ffffff; /* Override the global background for the right side */
        }

        .split-layout {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        /* Left Side: Image & Branding */
        .split-left {
            flex: 1;
            /* Linear gradient acts as a maroon tint overlay to make text readable */
            background: linear-gradient(
                rgba(138, 21, 56, 0.8),
                rgba(96, 15, 36, 0.9)
            ), url('../../assets/bg.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            color: white;
            position: relative;
        }

        .split-left .branding {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .split-left .brand-logo {
            height: 50px;
            width: auto;
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(244, 196, 48, 0.4);
        }

        .split-left h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--citu-gold);
            background: none;
            -webkit-text-fill-color: var(--citu-gold); /* Override the global gradient text here */
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .split-left p {
            font-size: 1.2rem;
            font-weight: 300;
            max-width: 80%;
            opacity: 0.9;
        }

        /* Right Side: Login Form */
        .split-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            padding: 2rem;
        }

        .login-panel {
            width: 100%;
            max-width: 420px;
            animation: fadeInUp 0.6s ease-out;
        }

        .login-panel h2 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .login-panel p.subtitle {
            color: #64748b;
            margin-bottom: 2.5rem;
            font-size: 0.95rem;
        }

        /* Clean, floating-style inputs */
        .login-panel .form-group {
            margin-bottom: 1.5rem;
        }

        .login-panel label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .login-panel input {
            background-color: #f8fafc;
            border: 2px solid transparent;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            box-shadow: none;
        }

        .login-panel input:focus {
            background-color: #ffffff;
            border-color: var(--citu-maroon);
            box-shadow: 0 4px 20px rgba(138, 21, 56, 0.1);
        }

        .login-panel .btn {
            margin-top: 1rem;
            padding: 1rem;
            font-size: 1rem;
            border-radius: 12px;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .split-layout {
                flex-direction: column;
            }
            .split-left {
                flex: 0.4;
                padding: 2rem;
                justify-content: flex-end;
            }
            .split-left h1 {
                font-size: 2rem;
            }
            .split-right {
                flex: 0.6;
                padding: 2rem 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="split-layout">
        <div class="split-left">
            <div class="branding">
                <img src="../../assets/logo.png" alt="CIT-U Logo" class="brand-logo">
                <span style="font-weight: 700; letter-spacing: 1px; color: white;">WILDFINDS</span>
            </div>
            <h1>Welcome Back,<br>Wildcat.</h1>
            <p>"Securely managing found items and ensuring no Wildcat is left behind."</p>
        </div>

        <div class="split-right">
            <div class="login-panel">
                <h2>Faculty Login</h2>
                <p class="subtitle">Enter your credentials to access the dashboard.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" style="border-radius: 12px; font-size: 0.9rem;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="employee_id">Employee ID</label>
                        <input type="text" id="employee_id" name="employee_id" placeholder="e.g. EMP001" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn" style="width: 100%;">Log In to Dashboard</button>
                    <div style="text-align: center; margin-top: 1.5rem;">
                                            <a href="../dashboard/public_dashboard.php" style="color: var(--citu-maroon); text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: all 0.3s;">
                                                &larr; Return to Public Dashboard
                                            </a>
                                        </div>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </body>
                    </html>
