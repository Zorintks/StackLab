const express = require('express');
const nodemailer = require('nodemailer');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

app.post('/send-email', (req, res) => {
    const { name, email, phone, subject, message } = req.body;

    // Configurar o transportador do Nodemailer
    let transporter = nodemailer.createTransport({
        service: 'gmail', // ou outro serviço SMTP
        auth: {
            user: 'stacklab.go@gmail.com',
            pass: '@dmlabgo2134@' // Gmail exige senha de app
        }
    });

    let mailOptions = {
        from: email,
        to: 'seuemail@gmail.com', // seu e-mail
        subject: subject || 'Nova mensagem do site StackLab',
        text: `Nome: ${name}\nEmail: ${email}\nTelefone: ${phone}\nMensagem: ${message}`
    };

    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            console.log(error);
            return res.status(500).send('Erro ao enviar e-mail.');
        }
        console.log('Email enviado: ' + info.response);
        res.status(200).send('Mensagem enviada com sucesso!');
    });
});

app.listen(3000, () => {
    console.log('Servidor rodando na porta 3000');
});
