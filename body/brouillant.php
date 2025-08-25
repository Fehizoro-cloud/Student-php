* {
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding-top: 5px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            background: #f5f5f5;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .checkbox-item input {
            margin-right: 6px;
        }

        .error, .success {
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .error {
            background-color: #ffe6e6;
            color: #cc0000;
        }

        .success {
            background-color: #e6ffe6;
            color: #006600;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #5a67d8;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #434190;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
        .inline-button {
    display: inline-block;
}
    
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        .checkbox-group { display: flex; flex-wrap: wrap; gap: 10px; }
        .checkbox-item { display: flex; align-items: center; }
        .checkbox-item input { margin-right: 5px; }
        .error { color: red; margin-bottom: 15px; }
        .success { color: green; margin-bottom: 15px; }