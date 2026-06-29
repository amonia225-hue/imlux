import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';

import '../models/souscription.dart';
import '../services/api_service.dart';
import '../services/auth_service.dart';
import '../theme/app_theme.dart';
import '../utils/format.dart';
import '../widgets/ui.dart';
import 'notifications_screen.dart';
import 'souscription_detail_screen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  late Future<List<Souscription>> _future;

  @override
  void initState() {
    super.initState();
    _future = ApiService.souscriptions();
  }

  Future<void> _refresh() async {
    setState(() => _future = ApiService.souscriptions());
    await _future;
  }

  @override
  Widget build(BuildContext context) {
    final souscripteur = context.watch<AuthService>().souscripteur;

    return Scaffold(
      body: SafeArea(
        child: RefreshIndicator(
          color: AppColors.gold,
          backgroundColor: AppColors.surface,
          onRefresh: _refresh,
          child: CustomScrollView(
            slivers: [
              SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.fromLTRB(20, 16, 20, 8),
                  child: Row(
                    children: [
                      Container(
                        width: 52,
                        height: 52,
                        alignment: Alignment.center,
                        decoration: const BoxDecoration(
                          gradient: AppColors.goldGradient,
                          shape: BoxShape.circle,
                        ),
                        child: Text(
                          souscripteur?.initials ?? '',
                          style: const TextStyle(
                              color: Color(0xFF1A1206),
                              fontWeight: FontWeight.w800),
                        ),
                      ),
                      const SizedBox(width: 14),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text('Bienvenue,',
                                style: TextStyle(
                                    color: AppColors.sage, fontSize: 13)),
                            Text(
                              souscripteur?.fullName ?? '',
                              style: AppTheme.display(22),
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                            if (souscripteur != null && souscripteur.fraisOuverture > 0)
                              Padding(
                                padding: const EdgeInsets.only(top: 4),
                                child: GestureDetector(
                                  onTap: souscripteur.fraisRecuUrl == null
                                      ? null
                                      : () => launchUrl(
                                            Uri.parse(souscripteur.fraisRecuUrl!),
                                            mode: LaunchMode.externalApplication,
                                          ),
                                  child: StatusChip(
                                    label: souscripteur.fraisOuverturePayes
                                        ? 'Frais de dossier payés · Reçu'
                                        : 'Frais de dossier : ${fcfa(souscripteur.fraisOuverture)}',
                                    status: souscripteur.fraisOuverturePayes ? 'termine' : 'annule',
                                  ),
                                ),
                              ),
                          ],
                        ),
                      ),
                      _NotificationBell(unread: souscripteur?.notificationsNonLues ?? 0),
                      IconButton(
                        tooltip: 'Déconnexion',
                        icon: const Icon(Icons.logout, color: AppColors.sage),
                        onPressed: () => context.read<AuthService>().logout(),
                      ),
                    ],
                  ),
                ),
              ),
              SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.fromLTRB(20, 8, 20, 12),
                  child: Text('Mes biens',
                      style: AppTheme.display(20, FontWeight.w600)),
                ),
              ),
              SliverFillRemaining(
                hasScrollBody: true,
                child: FutureBuilder<List<Souscription>>(
                  future: _future,
                  builder: (context, snap) {
                    if (snap.connectionState == ConnectionState.waiting) {
                      return const Center(
                          child: CircularProgressIndicator(
                              color: AppColors.gold));
                    }
                    if (snap.hasError) {
                      return _ErrorState(onRetry: _refresh);
                    }
                    final items = snap.data ?? [];
                    if (items.isEmpty) {
                      return const _EmptyState();
                    }
                    return ListView.separated(
                      padding: const EdgeInsets.fromLTRB(20, 0, 20, 24),
                      itemCount: items.length,
                      separatorBuilder: (_, _) => const SizedBox(height: 14),
                      itemBuilder: (_, i) => _SouscriptionCard(s: items[i]),
                    );
                  },
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _NotificationBell extends StatelessWidget {
  final int unread;
  const _NotificationBell({required this.unread});

  @override
  Widget build(BuildContext context) {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        IconButton(
          tooltip: 'Notifications',
          icon: const Icon(Icons.notifications_outlined, color: AppColors.gold),
          onPressed: () => Navigator.push(
            context,
            MaterialPageRoute(builder: (_) => const NotificationsScreen()),
          ),
        ),
        if (unread > 0)
          Positioned(
            right: 6,
            top: 6,
            child: Container(
              padding: const EdgeInsets.all(4),
              constraints: const BoxConstraints(minWidth: 18, minHeight: 18),
              decoration: const BoxDecoration(
                  color: AppColors.danger, shape: BoxShape.circle),
              child: Text(
                unread > 9 ? '9+' : '$unread',
                textAlign: TextAlign.center,
                style: const TextStyle(
                    color: Colors.white,
                    fontSize: 10,
                    fontWeight: FontWeight.bold),
              ),
            ),
          ),
      ],
    );
  }
}

class _SouscriptionCard extends StatelessWidget {
  final Souscription s;
  const _SouscriptionCard({required this.s});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () => Navigator.push(
        context,
        MaterialPageRoute(
            builder: (_) => SouscriptionDetailScreen(souscriptionId: s.id)),
      ),
      child: SectionCard(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(s.programme.name, style: AppTheme.display(20)),
                      const SizedBox(height: 2),
                      Text(
                        'Lot ${s.lot.reference} · ${s.lot.typeLogement}',
                        style: const TextStyle(
                            color: AppColors.sage, fontSize: 13),
                      ),
                    ],
                  ),
                ),
                StatusChip(label: s.statusLabel, status: s.status),
              ],
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                _miniStat('Payé', fcfa(s.totalVerse), AppColors.success),
                const SizedBox(width: 12),
                _miniStat('Reste', fcfa(s.resteAPayer),
                    s.resteAPayer > 0 ? AppColors.gold : AppColors.success),
              ],
            ),
            const SizedBox(height: 16),
            _labeledBar('Paiement', s.progressPercent),
            const SizedBox(height: 12),
            _labeledBar('Chantier', s.programme.avancementGlobal.toDouble()),
          ],
        ),
      ),
    );
  }

  Widget _miniStat(String label, String value, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(12),
        decoration: BoxDecoration(
          color: AppColors.surface2,
          borderRadius: BorderRadius.circular(14),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(label,
                style: const TextStyle(color: AppColors.sage, fontSize: 11)),
            const SizedBox(height: 4),
            Text(value,
                style: TextStyle(
                    color: color, fontWeight: FontWeight.w800, fontSize: 14)),
          ],
        ),
      ),
    );
  }

  Widget _labeledBar(String label, double percent) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(label,
                style: const TextStyle(color: AppColors.sage, fontSize: 12)),
            Text('${percent.round()}%',
                style: const TextStyle(
                    color: AppColors.gold,
                    fontSize: 12,
                    fontWeight: FontWeight.w700)),
          ],
        ),
        const SizedBox(height: 6),
        GradientProgressBar(percent: percent, height: 8),
      ],
    );
  }
}

class _EmptyState extends StatelessWidget {
  const _EmptyState();
  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        const SizedBox(height: 80),
        const Icon(Icons.home_work_outlined, size: 56, color: AppColors.sage),
        const SizedBox(height: 16),
        Center(
          child: Text('Aucune adhésion',
              style: AppTheme.display(20, FontWeight.w600)),
        ),
        const SizedBox(height: 8),
        const Center(
          child: Text('Aucun bien associé à votre compte.',
              style: TextStyle(color: AppColors.sage)),
        ),
      ],
    );
  }
}

class _ErrorState extends StatelessWidget {
  final VoidCallback onRetry;
  const _ErrorState({required this.onRetry});
  @override
  Widget build(BuildContext context) {
    return ListView(
      children: [
        const SizedBox(height: 80),
        const Icon(Icons.cloud_off, size: 56, color: AppColors.sage),
        const SizedBox(height: 16),
        const Center(
          child: Text('Impossible de charger vos données',
              style: TextStyle(color: AppColors.ivory, fontSize: 16)),
        ),
        const SizedBox(height: 16),
        Center(
          child: TextButton(
              onPressed: onRetry,
              child: const Text('Réessayer',
                  style: TextStyle(color: AppColors.gold))),
        ),
      ],
    );
  }
}
