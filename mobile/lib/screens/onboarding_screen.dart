import 'package:flutter/material.dart';

import '../config.dart';
import '../theme/app_theme.dart';
import '../widgets/ui.dart';
import 'login_screen.dart';
import 'signup_screen.dart';

/// Écran d'accueil (non connecté) : présente le bien-fondé du projet IMLUX
/// et propose de créer un compte ou de se connecter.
class OnboardingScreen extends StatelessWidget {
  const OnboardingScreen({super.key});

  static const _points = [
    (
      Icons.key_outlined,
      'Devenez propriétaire',
      "Accédez à des programmes fonciers et résidentiels avec un apport initial, puis un échéancier sur mesure — mensuel, trimestriel ou annuel.",
    ),
    (
      Icons.timeline_outlined,
      'Suivez tout en temps réel',
      "Versements, attestations, reçus et avancement des travaux de votre logement — avec une notification à chaque étape.",
    ),
    (
      Icons.handshake_outlined,
      'Accompagnement IMLUX',
      "Un conseiller dédié vous guide, de la première pierre jusqu'à la remise de votre titre de propriété. En toute transparence.",
    ),
  ];

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
          child: SingleChildScrollView(
            padding: const EdgeInsets.fromLTRB(24, 28, 24, 32),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const Center(child: LogoBadge(size: 104)),
                const SizedBox(height: 24),
                Text('Réalisez votre rêve\nimmobilier', textAlign: TextAlign.center, style: AppTheme.display(30)),
                const SizedBox(height: 12),
                const Text(
                  "IMLUX, c'est le bureau d'études qui rend la propriété accessible : un accompagnement clair, des échéances adaptées à votre budget, et un suivi de A à Z. Votre projet, notre métier.",
                  textAlign: TextAlign.center,
                  style: TextStyle(color: AppColors.sage, height: 1.6, fontSize: 14.5),
                ),
                const SizedBox(height: 28),
                ..._points.map((p) => Padding(
                      padding: const EdgeInsets.only(bottom: 14),
                      child: SectionCard(
                        child: Row(
                          children: [
                            Container(
                              width: 46,
                              height: 46,
                              alignment: Alignment.center,
                              decoration: BoxDecoration(
                                gradient: AppColors.goldGradient,
                                borderRadius: BorderRadius.circular(13),
                              ),
                              child: Icon(p.$1, color: const Color(0xFF1A1206)),
                            ),
                            const SizedBox(width: 14),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(p.$2, style: const TextStyle(color: AppColors.ivory, fontWeight: FontWeight.w700, fontSize: 15)),
                                  const SizedBox(height: 4),
                                  Text(p.$3, style: const TextStyle(color: AppColors.sage, height: 1.5, fontSize: 13)),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    )),
                const SizedBox(height: 12),
                GoldButton(
                  label: 'Créer mon compte',
                  onPressed: () => Navigator.of(context).push(
                    MaterialPageRoute(builder: (_) => const SignupScreen()),
                  ),
                ),
                const SizedBox(height: 10),
                TextButton(
                  onPressed: () => Navigator.of(context).push(
                    MaterialPageRoute(builder: (_) => const LoginScreen()),
                  ),
                  child: const Text(
                    "J'ai déjà un compte — Se connecter",
                    style: TextStyle(color: AppColors.ivory, fontWeight: FontWeight.w600),
                  ),
                ),
                const SizedBox(height: 18),
                Text(
                  '${AppConfig.appName} · ${AppConfig.appTagline}',
                  textAlign: TextAlign.center,
                  style: const TextStyle(color: AppColors.sage, fontSize: 12, letterSpacing: .5),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
