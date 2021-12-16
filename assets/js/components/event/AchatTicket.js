import React, { InvalidEvent } from 'react';
import Comments from '../../components/Comments';
import axios from 'axios';



class AchatTicket extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            tickets: [],
            loading: false,
            nom: '',
            email: '',
            tel: '',
            prix: 0,
            type: null,
            nombre: 1,
            ticketId: null,
            total: 0,
            errors: {
                name: null,
                email: null,
                tel: null,
                nombre: null
            }

        };

        this.handleTicketSubmit = this.handleTicketSubmit.bind(this);
        this.handleShowPaiement = this.handleShowPaiement.bind(this);
    }

    componentDidMount() {

        fetch('/' + window.locale + '/event/json_event_tickets/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                //console.log('tickets...', entries)
                this.setState({
                    tickets: entries,
                    loading: true
                });

            });

         const script = document.createElement("script");          
          script.src = "https://www.wecashup.com/library/MobileMoney.js";
          script.async = true;  
          document.body.appendChild(script);
    }

    handleTicketScroll(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.lst-elm-ticket').addClass('bounceInDown2').removeClass('d-none');
    }


    handlePaiementClick(e) {
        $('#WCUpaymentButton').click();
    }



    handleTicketAction(e, id, prix, type) {

        this.setState({ prix: prix });
        this.setState({ ticketId: id });
        this.setState({ type: type });
        this.setState({ nombre: 1 });

        e.preventDefault();
        e.stopPropagation();
        $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');


        if (window.userId > 0 && prix == 0) {//si le user est connecté
            axios.post('/' + window.locale + '/event/json_manage_ticket/' + id, {
                prix: this.state.prix,
            }).then(response => {
                this.setState({ nombre: 1 });

                //console.log('succes....',response.data.message)
                let message = response.data.message;
                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div class="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i class="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br><span class="fs-12 text-white-50">NB: ' + message + '.</span><div class="text-right"> <button type="button" class="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }).catch(error => {
                this.setState({ nombre: 1 });
                let errorObject = JSON.parse(JSON.stringify(error));
                let message = errorObject.response.data.message;

                $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
                //$(this).parent().parent().addClass('d-none b-tkt');
                $('.cont-tik').removeClass('siut').addClass('d-none');

                $('.cont-all-plst').append('<div class="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i class="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br><span class="fs-12 text-white-50">' + message + '</span><div class="text-right"> <button type="button" class="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }
            );
        } else {
            $('.cont-tik').removeClass('d-none sout').addClass('siut');
        }

    }

    handleShowPaiement(e) {
        //console.log('nianag....................')
        const errors = this.validate(this.state.nom, this.state.tel, this.state.email, this.state.nombre);
        if (errors) {
            // do something with errors
            return;
        }

        $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
        //$(this).parent().parent().addClass('d-none b-tkt');
        $('.cont-tik').removeClass('siut').addClass('d-none');
        //on acitve la dialogue du paiement
        $('.cont-pay').removeClass('d-none sout').addClass('siut');
    }

    handleTicketSubmit(e) {

        console.log('je sousmet le form')
        const errors = this.validate(this.state.nom, this.state.tel, this.state.email, this.state.nombre);
        if (errors) {
            // do something with errors
            return;
        }


        axios.post('/' + window.locale + '/event/json_manage_ticket/' + this.state.ticketId, {
            email: this.state.email,
            nom: this.state.nom,
            tel: this.state.tel,
            nombre: this.state.nombre,
            prix: this.state.prix,
        }).then(response => {
            this.setState({ nombre: 1 });

            //console.log('succes....',response.data.message)
            let message = response.data.message;
            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div class="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i class="fa fa-thumbs-up mr-2 text-success"></i>Votre demande a été prise en compte. <br><span class="fs-12 text-white-50">NB: ' + message + '.</span><div class="text-right"> <button type="button" class="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }).catch(error => {
            console.log('error....', error)
            this.setState({ nombre: 1 });
            let errorObject = JSON.parse(JSON.stringify(error));
            let message = errorObject.response.data.message;

            $('.lst-elm-ticket').addClass('d-none ').removeClass('bounceInDown2');
            //$(this).parent().parent().addClass('d-none b-tkt');
            $('.cont-tik').removeClass('siut').addClass('d-none');
            $('.cont-pay').removeClass('siut').addClass('d-none');

            $('.cont-all-plst').append('<div class="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i class="fa fa-times mr-2 text-danger"></i> Votre demande n\'a pas été effectuée. <br><span class="fs-12 text-white-50">' + message + '</span><div class="text-right"> <button type="button" class="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }
        );
    }

    handleContentChange(e) {
        const name = e.target.name;
        const value = e.target.value;
        this.setState({ [name]: value });
    }

    validate(name, tel, email, nombre) {
        let token = null;
        let errors = {};


        if (this.state.prix > 0) {//ticket payant
            if (nombre <= 0) {
                errors.nombre = " :Le nombre de tickets doit etre au  moins 1";
                token = "errors";
            }

            if (window.userId == 0) {//invite
                if (name.length === 0) {
                    errors.name = " :Le nom ne doit pas etre vide";
                    token = "errors";
                }

                if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
                    errors.email = " :Le mail n'est pas valide";
                    token = "errors";
                }
            }
        } else { //ticket gratuit
            if (window.userId == 0) {//invite
                if (name.length === 0) {
                    errors.name = " :Le nom ne doit pas etre vide";
                    token = "errors";
                }

                if (tel.length === 0) {
                    errors.tel = " :Veuillez renseigner votre téléphone";
                    token = "errors";
                }


                if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
                    errors.email = " :Le mail n'est pas valide";
                    token = "errors";
                }
            }
        }

        this.setState({ errors: errors });
        return token;
    }


    render() {

        const website = `${window.location.protocol}//${window.location.hostname}:${window.location.port}/`;
        const urlpay = website + window.locale + '/pay/wecashup';
        const logo = website + 'kossa/img/kossa.png';

        return (
            <div >
                {this.state.loading == true ?
                    <div>
                        {this.state.tickets.length === 0 &&
                            <div className="animated position-relative z1-10 ">
                                <span className="btn btn-danger btn-sm p-1 pl-3 pr-3 ml-0 font-weight-bold ">Le quota de tickets a été atteint</span>
                            </div>
                        }

                        {(this.state.tickets.length == 1 && this.state.tickets[0].prix == 0) &&
                            <div className="animated position-relative z1-10 ">
                                <span className="btn btn-danger btn-sm p-1 pl-3 pr-3 ml-0 font-weight-bold elm-ticket" onClick={(e) => this.handleTicketAction(e, this.state.tickets[0].id, this.state.tickets[0].prix, this.state.tickets[0].type)}>Participer</span>
                            </div>
                        }

                        {(this.state.tickets.length == 1 && this.state.tickets[0].prix > 0) &&
                            <div className="animated position-relative z1-10 tkdet ">
                                <span className="btn btn-danger btn-sm p-1 pl-3 pr-3 ml-0 font-weight-bold btn-cate-ticket" onClick={this.handleTicketScroll.bind(this)}>Achetez un ticket</span>

                                <div className="position-absolute d-none btn-danger p-0 w-100 lst-elm-ticket z-depth-2 animated " style={{ top: '0px', left: '0px' }}>
                                    {this.state.tickets.map(
                                        ({ id, prix, type }) => (
                                            <div key={id} className="p-1 pl-3 pr-3 font-weight-bold fs-12 text-capitalize ls3 elm-ticket cur-pointer" onClick={(e) => this.handleTicketAction(e, id, prix, type)}>{type}</div>
                                        )
                                    )
                                    }

                                </div>
                            </div>
                        }

                        {(this.state.tickets.length > 1) &&
                            <div className="animated position-relative z1-10 tkdet ">
                                <span className="btn btn-danger btn-sm p-1 pl-3 pr-3 ml-0 font-weight-bold btn-cate-ticket" onClick={this.handleTicketScroll.bind(this)} >Achetez un ticket</span>

                                <div className="position-absolute d-none btn-danger p-0 w-100 lst-elm-ticket z-depth-2 animated " style={{ top: '0px', left: '0px' }}>

                                    {this.state.tickets.map(
                                        ({ id, prix, type }) => (
                                            <div key={id} className="p-1 pl-3 pr-3 font-weight-bold fs-12 text-capitalize ls3 elm-ticket cur-pointer" onClick={(e) => this.handleTicketAction(e, id, prix, type)}>{type}</div>
                                        )
                                    )
                                    }

                                </div>
                            </div>
                        }
                    </div> :
                    <div>
                        Patientez...
                </div>
                }

                <div className=" position-fixed h-100 w-100 bg-event-pop cont-tik d-none zitp animated" style={{ top: '0px', left: '0px' }}>
                    <div className="h-100 w-100 d-flex justify-content-center align-items-center ">
                        <div className=" white p-4 z-depth-1">
                            <div className="position-relative p-3 rounded-bottom">
                                <div className="position-absolute pl-2 pr-2 roud-close pt-1 pb-1 bnt-closet cur-pointer " style={{ top: '0px', right: '0px' }}>
                                    <i className="fa fa-plus rot-90 fs-12 "></i>
                                </div>
                                <div className="text-center">
                                    <span ><img src="/kossa/img/kossa.png" className="mascot3" alt="" /></span>
                                </div>
                                {this.state.prix > 0 ?
                                    <div>
                                        <div className="mt-1 text-black-50 fs-16 text-center">Ticket <span className="tpticket cl-r1 fs-18">{this.state.type}</span></div>
                                        <div className="cl-n2 fs-10 mb-4 text-center "> <span className="prx-ori badge badge-primary"></span>{this.state.prix} xaf </div>
                                    </div>
                                    :

                                    <div>
                                        <div className="mt-1 text-black-50 fs-16 text-center mb-4">Obtenir votre ticket gratuitement</div>
                                    </div>
                                }
                                {window.userId == 0 &&
                                    <div>
                                        <div className="input-group mb-2">
                                            <label htmlFor="nom" className={this.state.errors.name ? 'mb-0 ls3 fs-12 text-red' : 'mb-0 ls3 fs-12'}>Nom et prénom* {this.state.errors.name}</label>
                                            <input value={this.state.nom} onChange={this.handleContentChange.bind(this)} type="tel" id="nom" name="nom" className="w-100 p-2 mt-0 trs3  inp-tk" placeholder="Entrez votre nom" />
                                        </div>

                                        {this.state.prix == 0 &&
                                            <div className="input-group mb-2">
                                                <label htmlFor="num" className={this.state.errors.tel ? 'mb-0 ls3 fs-12 text-red' : 'mb-0 ls3 fs-12'} >Numéro de téléphone* {this.state.errors.tel}</label>
                                                <input value={this.state.tel} onChange={this.handleContentChange.bind(this)} type="tel" id="num" name="tel" className="w-100 p-2 mt-0 trs3  inp-tk" placeholder="Entrez votre numéro de téléphone" />
                                            </div>
                                        }

                                        <div className="input-group mb-2 ">
                                            <label htmlFor="mail" className={this.state.errors.email ? 'mb-0 ls3 fs-12 text-red' : 'mb-0 ls3 fs-12'} >Addrese mail* {this.state.errors.email}</label>
                                            <input value={this.state.email} onChange={this.handleContentChange.bind(this)} type="email" id="mail" name="email" className="w-100 p-2 mt-0 trs3 " placeholder="Entrez votre addrese mail" />
                                        </div>
                                    </div>
                                }

                                {this.state.prix > 0 &&
                                    <div className="input-group mb-2">
                                        <label htmlFor="nbt" className={this.state.errors.nombre ? 'mb-0 ls3 fs-12 text-red' : 'mb-0 ls3 fs-12'} >Nombre de ticket* {this.state.errors.nombre}</label>
                                        <input value={this.state.nombre} onChange={this.handleContentChange.bind(this)} type="number" id="nbt" name="nombre" className="w-100 p-2 mt-0 trs3  inp-tk" placeholder="Entrez le nombre de ticket" />
                                    </div>
                                }
                                <div className="text-black-50 text-right">
                                    <span className="">Prix total: </span>
                                    <span className="fs-18 font-weight-bold cl-n2" >{this.state.nombre * this.state.prix} </span>
                                    <span className="">xaf</span>
                                </div>
                                <div className="input-group mt-1 p-0 ">
                                    {this.state.prix > 0 ?
                                        <button onClick={this.handlePaiementClick.bind(this)} type="button" id="WCUpaymentButton" data-featherlight="#payment_box" data-featherlight-close-on-click="false"
                                            className="btn btn-purple btn-sm btn-rounded font-weight-bold ml-0 mr-0">Acheter
                                            </button>
                                        :
                                        <button onClick={this.handleTicketSubmit} type="button" className="btn btn-purple btn-sm btn-rounded font-weight-bold ml-0 mr-0">
                                            Envoyer
                                    </button>
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="paiement" className=" position-fixed h-100 w-100 bg-event-pop cont-pay2 d-none zitp animated" style={{ top: '0px', left: '0px' }}>
                    <form action={urlpay} method="POST" id="wecashup">
                        <script async class="wecashup_button"
                            data-sender-lang={window.locale}
                            data-sender-phonenumber=""
                            data-receiver-uid="DIQuBxe30CdCV8tI13o6VjpEwf62"
                            data-receiver-public-key="pk_test_JaMjtNl5Yf0N256U"
                            data-transaction-parent-uid=""
                            data-transaction-receiver-total-amount="1000"
                            data-transaction-receiver-reference="XVT2VBF"
                            data-transaction-sender-reference="XVT2VBF"
                            data-sender-firstname="Test"
                            data-sender-lastname="Test"
                            data-transaction-method="pull"
                            data-image={logo}
                            data-name="KOSSA"
                            data-crypto="true"
                            data-cash="true"
                            data-telecom="true"
                            data-m-wallet="true"
                            data-split="true"
                            configuration-id="3"
                            data-marketplace-mode="false"
                            data-product-1-name="tiket pour evenement"
                            data-product-1-quantity="1"
                            data-product-1-unit-price="10000"
                            data-product-1-reference="XVT2VBF"
                            data-product-1-category="Billeterie"
                            data-product-1-description="pour participer à un evenement"
                        >
                        </script>
                    </form>
                </div>
            </div>
        );
    }
}

export default AchatTicket;

/*
<form action="https://www.wecashup.cloud/cdn/tests/websites/PHP/callback_lucas.php" method="POST" id="wecashup">
<script async="" src="https://www.wecashup.com/library/MobileMoney.js" class="wecashup_button" data-demo="" data-sender-lang="en" data-sender-phonenumber="+237671234567" data-receiver-uid="EvIvZFlBKNaMddjXJOOpEWNeWj52" data-receiver-public-key="kCAc2vOwcANrbdKCuFnXLhS76yMx3f8iUytCbN8Drx6T" data-transaction-parent-uid="" data-transaction-receiver-total-amount="594426" data-transaction-receiver-reference="XVT2VBF" data-transaction-sender-reference="XVT2VBF" data-sender-firstname="Test" data-sender-lastname="Test" data-transaction-method="pull" data-image="https://storage.googleapis.com/wecashup/frontend/img/airfrance.png" data-name="Air France" data-cash="true" data-crypto="true" data-telecom="true" data-m-wallet="true" data-split="true" configuration-id="3" data-marketplace-mode="false" data-product-1-name="Billet ABJ PRS" data-product-1-quantity="1" data-product-1-unit-price="594426" data-product-1-reference="XVT2VBF" data-product-1-category="Billeterie" data-product-1-description="France is in the Air">
        </script><button type="button" data-featherlight="#payment_box" data-featherlight-close-on-click="false" id="WCUpaymentButton">Pay now</button>
</form> */


















