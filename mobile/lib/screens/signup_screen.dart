import 'package:flutter/material.dart';

import '../services/api_service.dart';
import '../theme/app_theme.dart';
import '../widgets/ui.dart';

/// Création de compte client depuis l'app.
/// Le compte est créé EN ATTENTE de validation par le cabinet IMLUX ;
/// le client pourra se connecter une fois son accès activé.
class SignupScreen extends StatefulWidget {
  const SignupScreen({super.key});

  @override
  State<SignupScreen> createState() => _SignupScreenState();
}

class _SignupScreenState extends State<SignupScreen> {
  final _formKey = GlobalKey<FormState>();
  final _firstName = TextEditingController();
  final _lastName = TextEditingController();
  final _email = TextEditingController();
  final _phone = TextEditingController();
  final _address = TextEditingController();
  final _password = TextEditingController();
  DateTime? _birth;
  bool _loading = false;
  bool _obscure = true;
  String? _error;

  @override
  void dispose() {
    _firstName.dispose();
    _lastName.dispose();
    _email.dispose();
    _phone.dispose();
    _address.dispose();
    _password.dispose();
    super.dispose();
  }

  String _fmt(DateTime d) =>
      '${d.year.toString().padLeft(4, '0')}-${d.month.toString().padLeft(2, '0')}-${d.day.toString().padLeft(2, '0')}';

  Future<void> _pickBirth() async {
    final now = DateTime.now();
    final picked = await showDatePicker(
      context: context,
      initialDate: DateTime(now.year - 30),
      firstDate: DateTime(now.year - 100),
      lastDate: now,
      helpText: 'Date de naissance',
    );
    if (picked != null) setState(() => _birth = picked);
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    if (_birth == null) {
      setState(() => _error = 'Veuillez indiquer votre date de naissance.');
      return;
    }
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final message = await ApiService.register({
        'first_name': _firstName.text.trim(),
        'last_name': _lastName.text.trim(),
        'email': _email.text.trim(),
        'phone': _phone.text.trim(),
        'date_naissance': _fmt(_birth!),
        'address': _address.text.trim().isEmpty ? null : _address.text.trim(),
        'password': _password.text,
      });
      if (!mounted) return;
      await showDialog<void>(
        context: context,
        barrierDismissible: false,
        builder: (_) => AlertDialog(
          backgroundColor: AppColors.surface,
          icon: const Icon(Icons.mark_email_read_outlined, color: AppColors.gold, size: 40),
          title: const Text('Compte créé', style: TextStyle(color: AppColors.ivory)),
          content: Text(message, style: const TextStyle(color: AppColors.sage, height: 1.5)),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Compris', style: TextStyle(color: AppColors.gold, fontWeight: FontWeight.w700)),
            ),
          ],
        ),
      );
      if (mounted) Navigator.of(context).pop(); // retour à l'accueil
    } on ApiException catch (e) {
      setState(() => _error = e.message);
    } catch (_) {
      setState(() => _error = 'Inscription impossible. Vérifiez votre réseau.');
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  InputDecoration _dec(String label, IconData icon) => InputDecoration(
        labelText: label,
        prefixIcon: Icon(icon, color: AppColors.gold),
      );

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Créer mon compte')),
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [AppColors.emeraldDark, AppColors.bg],
          ),
        ),
        child: SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(20),
            child: SectionCard(
              padding: const EdgeInsets.all(20),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    Text('Rejoignez IMLUX', style: AppTheme.display(22)),
                    const SizedBox(height: 6),
                    const Text(
                      "Quelques informations et un conseiller activera votre accès.",
                      style: TextStyle(color: AppColors.sage, fontSize: 13.5, height: 1.5),
                    ),
                    const SizedBox(height: 20),
                    if (_error != null) ...[
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: AppColors.danger.withValues(alpha: 0.12),
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: AppColors.danger.withValues(alpha: 0.3)),
                        ),
                        child: Text(_error!, style: const TextStyle(color: AppColors.danger, fontSize: 13)),
                      ),
                      const SizedBox(height: 16),
                    ],
                    TextFormField(
                      controller: _firstName,
                      style: const TextStyle(color: AppColors.ivory),
                      decoration: _dec('Prénom', Icons.person_outline),
                      validator: (v) => (v == null || v.trim().isEmpty) ? 'Prénom requis' : null,
                    ),
                    const SizedBox(height: 14),
                    TextFormField(
                      controller: _lastName,
                      style: const TextStyle(color: AppColors.ivory),
                      decoration: _dec('Nom', Icons.badge_outlined),
                      validator: (v) => (v == null || v.trim().isEmpty) ? 'Nom requis' : null,
                    ),
                    const SizedBox(height: 14),
                    TextFormField(
                      controller: _email,
                      keyboardType: TextInputType.emailAddress,
                      style: const TextStyle(color: AppColors.ivory),
                      decoration: _dec('Adresse email', Icons.mail_outline),
                      validator: (v) => (v == null || !v.contains('@')) ? 'Email invalide' : null,
                    ),
                    const SizedBox(height: 14),
                    TextFormField(
                      controller: _phone,
                      keyboardType: TextInputType.phone,
                      style: const TextStyle(color: AppColors.ivory),
                      decoration: _dec('Téléphone', Icons.phone_outlined),
                      validator: (v) => (v == null || v.trim().length < 8) ? 'Téléphone invalide' : null,
                    ),
                    const SizedBox(height: 14),
                    InkWell(
                      onTap: _pickBirth,
                      borderRadius: BorderRadius.circular(12),
                      child: InputDecorator(
                        decoration: _dec('Date de naissance', Icons.cake_outlined),
                        child: Text(
                          _birth == null ? 'Choisir une date' : _fmt(_birth!),
                          style: TextStyle(color: _birth == null ? AppColors.sage : AppColors.ivory),
                        ),
                      ),
                    ),
                    const SizedBox(height: 14),
                    TextFormField(
                      controller: _address,
                      style: const TextStyle(color: AppColors.ivory),
                      decoration: _dec('Adresse (facultatif)', Icons.home_outlined),
                    ),
                    const SizedBox(height: 14),
                    TextFormField(
                      controller: _password,
                      obscureText: _obscure,
                      style: const TextStyle(color: AppColors.ivory),
                      decoration: InputDecoration(
                        labelText: 'Mot de passe',
                        prefixIcon: const Icon(Icons.lock_outline, color: AppColors.gold),
                        suffixIcon: IconButton(
                          icon: Icon(_obscure ? Icons.visibility_outlined : Icons.visibility_off_outlined, color: AppColors.sage),
                          onPressed: () => setState(() => _obscure = !_obscure),
                        ),
                      ),
                      validator: (v) => (v == null || v.length < 6) ? '6 caractères minimum' : null,
                    ),
                    const SizedBox(height: 24),
                    GoldButton(label: 'Créer mon compte', loading: _loading, onPressed: _submit),
                    const SizedBox(height: 8),
                    TextButton(
                      onPressed: () => Navigator.of(context).pop(),
                      child: const Text('Retour', style: TextStyle(color: AppColors.sage)),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
