import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../config.dart';
import '../services/api_service.dart';
import '../services/auth_service.dart';
import '../theme/app_theme.dart';
import '../widgets/ui.dart';
import 'signup_screen.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _email = TextEditingController();
  final _password = TextEditingController();
  bool _loading = false;
  bool _obscure = true;
  String? _error;

  @override
  void dispose() {
    _email.dispose();
    _password.dispose();
    super.dispose();
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      await context.read<AuthService>().login(_email.text, _password.text);
      // Connecté : on retire l'écran de connexion empilé (AuthGate affiche l'accueil).
      if (mounted) Navigator.of(context).popUntil((route) => route.isFirst);
    } on ApiException catch (e) {
      setState(() => _error = e.message);
    } catch (_) {
      setState(() => _error = 'Connexion impossible. Vérifiez votre réseau.');
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [AppColors.emeraldDark, AppColors.bg],
          ),
        ),
        child: SafeArea(
          child: Center(
            child: SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: Column(
                children: [
                  const SizedBox(height: 20),
                  const LogoBadge(size: 120),
                  const SizedBox(height: 22),
                  Text('Espace adhérent', style: AppTheme.display(30)),
                  const SizedBox(height: 6),
                  const Text(
                    'Suivez vos versements, attestations\net l\'avancement de votre logement.',
                    textAlign: TextAlign.center,
                    style: TextStyle(color: AppColors.sage, height: 1.5),
                  ),
                  const SizedBox(height: 32),
                  SectionCard(
                    padding: const EdgeInsets.all(20),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          if (_error != null) ...[
                            Container(
                              padding: const EdgeInsets.all(12),
                              decoration: BoxDecoration(
                                color: AppColors.danger.withValues(alpha: 0.12),
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(
                                    color: AppColors.danger.withValues(alpha: 0.3)),
                              ),
                              child: Text(_error!,
                                  style: const TextStyle(
                                      color: AppColors.danger, fontSize: 13)),
                            ),
                            const SizedBox(height: 16),
                          ],
                          TextFormField(
                            controller: _email,
                            keyboardType: TextInputType.emailAddress,
                            style: const TextStyle(color: AppColors.ivory),
                            decoration: const InputDecoration(
                              labelText: 'Adresse email',
                              prefixIcon: Icon(Icons.mail_outline,
                                  color: AppColors.gold),
                            ),
                            validator: (v) => (v == null || !v.contains('@'))
                                ? 'Email invalide'
                                : null,
                          ),
                          const SizedBox(height: 16),
                          TextFormField(
                            controller: _password,
                            obscureText: _obscure,
                            style: const TextStyle(color: AppColors.ivory),
                            decoration: InputDecoration(
                              labelText: 'Mot de passe',
                              prefixIcon: const Icon(Icons.lock_outline,
                                  color: AppColors.gold),
                              suffixIcon: IconButton(
                                icon: Icon(
                                    _obscure
                                        ? Icons.visibility_outlined
                                        : Icons.visibility_off_outlined,
                                    color: AppColors.sage),
                                onPressed: () =>
                                    setState(() => _obscure = !_obscure),
                              ),
                            ),
                            validator: (v) => (v == null || v.isEmpty)
                                ? 'Mot de passe requis'
                                : null,
                            onFieldSubmitted: (_) => _submit(),
                          ),
                          const SizedBox(height: 24),
                          GoldButton(
                            label: 'Se connecter',
                            loading: _loading,
                            onPressed: _submit,
                          ),
                          const SizedBox(height: 6),
                          TextButton(
                            onPressed: () => Navigator.of(context).push(
                              MaterialPageRoute(builder: (_) => const SignupScreen()),
                            ),
                            child: const Text(
                              'Pas encore de compte ? Créer un compte',
                              style: TextStyle(color: AppColors.gold, fontWeight: FontWeight.w600),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 24),
                  Text(
                    '${AppConfig.appName} · ${AppConfig.appTagline}',
                    style: const TextStyle(
                        color: AppColors.sage, fontSize: 12, letterSpacing: .5),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
